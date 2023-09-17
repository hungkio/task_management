
<p class="
@if($status == 'Done')
    done
@elseif($status == 'Editing')
    editing
@else
    testing
@endif
    ">
{{$status}}
</p>
