

<div class="center-align">
    <ul class="pagination">

        @foreach( $pagination as $key => $page )
            <li class="{{ $page['class'] }}"><a href="#" data-page="{{$key}}">{{ $key }}</a></li>
        @endforeach

      </ul>
</div>
