<ul class="sidebar-menu" data-widget="tree"> 
  @foreach($items as $item)
    @if($item->children->count())  
      <li class="treeview">
          <a href="{{ $item['url'] }}">
            <i class="fa fa-fw fa-{{ $item['icon_class'] }}"></i><span>{{ $item['title'] }}</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a> 
          <ul class="treeview-menu">
          @foreach($item->children as $subitem)
            <li><a href="{{ $subitem['url'] }}"><i class="fa fa-circle-o text-red"></i><span> {{ $subitem['title']}} </span></a></li>
          @endforeach
          </ul>
        </li>   
        @else
                <li class="{{ $item['class'] }}">
                    <a href="{{ $item['url'] }}">
                        <i class="fa fa-fw fa-{{ $item['icon_class'] or 'circle-o' }} {{ isset($item['color']) ? 'text-' . $item['color'] : '' }}"></i>
                        <span>{{ $item['title'] }}</span>
                    </a>
                </li>
        @endif
    @endforeach
</ul>