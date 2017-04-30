@if(!empty($message))
    <div class="alert alert-{{explode('|', $message)[0]}} alert-styled-left">
        {{explode('|', $message)[1]}}
    </div>
@endif
@if(!empty(\Session::get('message')))
    <div class="alert alert-{{explode('|', \Session::get('message'))[0]}} alert-styled-left">
        {{explode('|', \Session::get('message'))[1]}}
    </div>
@endif