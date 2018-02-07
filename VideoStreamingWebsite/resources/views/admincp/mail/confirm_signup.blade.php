<div>
    <div><u></u>
        <div>
            <span>
                <span>
                   Hello {{$firstname}} {{$lastname}},
                   {{-- <span>
                    <span>
                        <h2>Thank you for registering, please

                            verify your account to prevent it going straight to spam which it does now.</h2>
                        </span>
                    </span> --}}
                    <p>
                        <span>
                            <span>Please follow this link to verify your account: <a target="_blank" href="{{URL(getLang().'register.html&action=active&token=')}}{{$token}}" rel="">{{URL(getLang().'register.html&action=active&token=')}}{{$token}}</a>
                            </span>

                        </span>
                    </p>
                </span>
            </span>

        </div>
        <div></div>
        <div>

        </div>
    </div>
</div>