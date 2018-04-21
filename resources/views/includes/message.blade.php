<div class="row justify-content-center">
    <div class="col-md-12 spacing-top text-center">
        @if(session('message'))
            <div class="alert alert-dismissible alert-{{session('alert_type')}}">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>{{ session('message') }}</strong>
            </div>
        @endif
    </div>
</div>