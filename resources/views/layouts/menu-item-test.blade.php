@foreach($items as $item)
    <li class="dropdown"><a target="{{ $item->target }}" href="{{ url($item->url) }}" data-toggle="dropdown" class="dropdown-toggle">{{ $item->title }}</a>
        @if($item->children->count())
            <ul role="menu" class="dropdown-menu">
                @foreach($item->children as $subItem)
                    <li>
                        <a target="{{ $subItem->target }}" href="{{ url($subItem->url) }}">{{ $subItem->title }}</a></li>
                    @if(!$loop->last) <li class="divider"></li> @endif
                @endforeach
            </ul>
        @endif
    </li>
@endforeach