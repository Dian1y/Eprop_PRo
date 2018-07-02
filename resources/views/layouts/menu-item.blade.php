 <ul class="sidebar-menu">
    <?php $items ?>
    @foreach($items as $item)    
        @if($item->children->count())  
            <li class="treeview active">
                <a href="{{ $item['url'] }}"
                   @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
                >
                    <i class="fa fa-fw fa-{{ $item['icon_class'] }}"></i>
                    <span>{{ $item['title'] }}</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                @foreach($item->children as $subitem)
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ $subitem['url'] }}"
                           @if (isset($subitem['target'])) target="{{ $subitem['target'] }}" @endif
                            >
                            <i class="fa fa-fw fa-circle-o text-red "></i>
                              <span>  {{ $subitem['title'] }} </span>
                            </a>
                        </li>
                    </ul>
                @endforeach
            </li>   
        @else
                <li class="{{ $item['class'] }}">
                    <a href="{{ $item['url'] }}"
                       @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
                    >
                        <i class="fa fa-fw fa-{{ $item['icon_class'] or 'circle-o' }} {{ isset($item['color']) ? 'text-' . $item['color'] : '' }}"></i>
                        <span>{{ $item['title'] }}</span>
                    </a>
                </li>
        @endif
    @endforeach
</ul>