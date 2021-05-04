<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

use ZBateson\MailMimeParser\MailMimeParser;
use ZBateson\MailMimeParser\Message as MailMessage;
use ZBateson\MailMimeParser\Header\HeaderConsts;

use App\User;
use Auth;

/**
 * A message is bvasically an email received in the application. 
 * It might at some point also be created by external contact forms for instance
 * 
 * It is short lived in the DB (a few days). It's purpose is to be converted to a discussion or a comment or... tbd
 */
class Message extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;

    protected $rules = [
        'subject'     => 'required',
        'raw'     => 'required',
    ];

    protected $keepRevisionOf = ['status'];
    
    protected $fillable = ['subject', 'to', 'from', 'body', 'raw', 'group_id', 'user_id', 'discussion_id', 'status'];


    public $timestamps = true;


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
     */
    public function isAutomated() 
    {
        /*
        Detect automatic messages and discard them, see here : https://www.arp242.net/autoreply.html
        */

        $message_headers = $this->headers();

        dd($message_headers);

        if (array_key_exists('Auto-Submitted', $message_headers)) {
            return true;
        }

        if (array_key_exists('X-Auto-Response-Suppress', $message_headers)) {
            return true;
        }

        if (array_key_exists('List-Id', $message_headers)) {
            return true;
        }

        if (array_key_exists('List-Unsubscribe', $message_headers)) {
            return true;
        }

        if (array_key_exists('Feedback-ID', $message_headers)) {
            return true;
        }

        if (array_key_exists('X-NIDENT', $message_headers)) {
            return true;
        }

        if (array_key_exists('Delivered-To', $message_headers)) {
            if ($message_headers['Delivered-To'] == 'Autoresponder') {
                return true;
            }
        }

        if (array_key_exists('X-AG-AUTOREPLY', $message_headers)) {
            return true;
        }



        return false;
    }

    


    /**
     * Parse headers of the raw email message and return everything as an array
     */
    function headers()
    {
        // Reference:
        // * Base: https://stackoverflow.com/questions/5631086/getting-x-mailer-attribute-in-php-imap/5631445#5631445
        // * Improved regex: https://stackoverflow.com/questions/5631086/getting-x-mailer-attribute-in-php-imap#comment61912182_5631445
        preg_match_all(
            '/([^:\s]+): (.*?(?:\r\n\s(?:.+?))*)\r\n/m',
            $this->raw,
            $matches
        );
        $headers = array_combine($matches[1], $matches[2]);
        return $headers;
    }


    /**
     * Returns a parsed representation of this message
     * So you can : 
     * $message->parse()->getTextContent(); 
     * $message->parse()->getHtmlContent(); 
     * 
     * See https://github.com/zbateson/mail-mime-parser
     * 
     */
    function parse()
    {
        return MailMessage::from($this->raw);
    }


}
