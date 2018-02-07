@if(isset($add))

<script src="{{URL::asset('public/assets/js/CodoPlayer.js')}}"></script>    
    <script>
    var  player =   CodoPlayer({
            title: "{{$add->title_name}}",
            poster:"{{$add->poster}}",
            src: "{{$add->video_src}}",
            srcHD: "{{$add->video_src}}"
        }, {
            width: 700, 
            height: 383,
            volume: 50,
            preload: false,
            priority: "src" ,
            plugins: {
                 share: true
            }
        });

    </script>
@endif