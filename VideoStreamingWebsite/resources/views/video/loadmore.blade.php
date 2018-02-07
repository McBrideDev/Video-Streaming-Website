<script src="{{URL::asset('public/assets/js/jquery.timeago.js')}}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function() {
$("abbr.timeago").timeago();
});
</script>
@if(isset($loadmore))
{{dumpComments($loadmore)}}
@if($loadmore->lastPage() == $loadmore->currentPage())
<script type="text/javascript">
    $(document).ready(function() {
        $('#loadmore').hide();
        $('#loadback').show();
    });
</script>
@endif
@if($loadmore->lastPage() == 1)
<script type="text/javascript">
    $(document).ready(function() {
        $('#loadmore').hide();
        $('#loadback').hide();
    });
</script>
@endif

@endif


