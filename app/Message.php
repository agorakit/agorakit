<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

use ZBateson\MailMimeParser\MailMimeParser;
use ZBateson\MailMimeParser\Message as MailMessage;
use ZBateson\MailMimeParser\Header\HeaderConsts;

use EmailReplyParser\EmailReplyParser;

use App\User;
use Auth;

/**
 * A message is basically an email received in the application. 
 * It might at some point also be created by external contact forms for instance (or an api ?)
 * 
 * It is short lived in the DB (a few days). It's purpose is to be converted to a discussion or a comment or... tbd
 */
class Message extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;

    protected $rules = [
        'raw'     => 'required',
    ];
    protected $keepRevisionOf = ['status'];
    protected $fillable = ['subject', 'to', 'from', 'body', 'raw', 'group_id', 'user_id', 'discussion_id', 'status'];
    public $timestamps = true;

    // Messages status, they all start at 0
    const POSTED = 100; // Message has been successfuly converted to discussion or wathever
    const NEEDS_VALIDATION = 10; // message needs to be validated by poster or admin
    const CREATED = 0; // message has just been imported from the mail server (default)
    const BOUNCED = -10; // message bounced back to user
    const INVALID = -20; // message cannot be posted to a group (group not found...)
    const AUTOMATED = -30; // message is an autoreply or away message
    const ERROR = -50; // message cound not be converted to content
    const SPAM = -100; // message is spam

    public function group()
    {
        return $this->belongsTo(\App\Group::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function discussion()
    {
        return $this->belongsTo(\App\Discussion::class);
    }

    /**
     * Returns true if message is an autoreply or vacation auto responder
     * see here : https://www.arp242.net/autoreply.html
     */
    public function isAutomated()
    {
        // $message_headers = $this->parse()->headers(); // TODO

        if ($this->parse()->getHeaderValue('Auto-Submitted')) {
            return true;
        }

        if ($this->parse()->getHeaderValue('X-Auto-Response-Suppress')) {
            return true;
        }

        if ($this->parse()->getHeaderValue('List-Id')) {
            return true;
        }

        if ($this->parse()->getHeaderValue('List-Unsubscribe')) {
            return true;
        }

        if ($this->parse()->getHeaderValue('Feedback-ID')) {
            return true;
        }

        if ($this->parse()->getHeaderValue('X-NIDENT')) {
            return true;
        }

        if ($this->parse()->getHeaderValue('Delivered-To')) {
            if ($this->parse()->getHeaderValue('Delivered-To') == 'Autoresponder') {
                return true;
            }
        }

        if ($this->parse()->getHeaderValue('X-AG-AUTOREPLY')) {
            return true;
        }


        return false;
    }


    /**
     * Returns a parsed representation of this message
     * So you can : 
     * $message->parse()->getTextContent(); 
     * $message->parse()->getHtmlContent(); 
     * 
     * Under the hood, uses https://github.com/zbateson/mail-mime-parser
     * 
     */
    function parse()
    {
        return MailMessage::from($this->raw);
    }

    /**
     * Returns a rich text represenation of the email, stripping away all quoted text, signatures, etc...
     */
    function extractText()
    {
        $body_text  = nl2br(EmailReplyParser::parseReply($this->parse()->getTextContent()));
        $body_html = $this->parse()->getHtmlContent();

        // count the number of caracters in plain text :
        // if we really have less than 5 chars in there using plain text,
        // let's post the whole html mess, 
        // converted to markdown, 
        // then stripped with the same EmailReplyParser, 
        // then converted from markdown back to html, pfeeew what could go wrong ?
        if (strlen($body_text) < 5) {
            $converter = new HtmlConverter();
            $markdown = $converter->convert($body_html);
            $result = Markdown::defaultTransform(EmailReplyParser::parseReply($markdown));
        } else {
            $result = $body_text;
        }

        return $result;
    }

    /**
     * Returns all recipients email of this message, using TO and CC fields. Parses the raw email content
     */
    function extractRecipients()
    {
        $recipients = [];

        if ($this->parse()->getHeader(HeaderConsts::TO)) {
            foreach ($this->parse()->getHeader(HeaderConsts::TO)->getAddresses() as $to) {
                $recipients[] = $to->getEmail();
            }
        }

        if ($this->parse()->getHeader(HeaderConsts::CC)) {
            foreach ($this->parse()->getHeader(HeaderConsts::CC)->getAddresses() as $to) {
                $recipients[] = $to->getEmail();
            }
        }

        return $recipients;
    }
}
