{{trans('membership.invite_email_intro')}}


{{trans('membership.invite_email_user')}} {{$invitating_user->name}}

{{trans('membership.invite_email_wants_to_invite_you_to')}} {{$group->name}}

{{trans('membership.invite_email_click_link_to_validate')}}

{{$invite->token}}

{{trans('membership.invite_email_outro')}}
