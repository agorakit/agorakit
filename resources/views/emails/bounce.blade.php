@component('mail::message')

# {{t('You sent an email that was not delivered')}}

## Reason : {{$reason}}

Original message subject : {{$subject}}

@component('mail::panel')
Original message body :
{{$body}}
@endcomponent


@endcomponent
