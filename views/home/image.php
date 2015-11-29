@extend('_layout')


@block('style')

<style>
    img{border: 2px solid crimson; max-width: 90%}
    img:after{content:" (" attr(src) ") ";font-size:0.8em;font-weight:normal;}


</style>

@endblock


@block('content')

    <h2>Loading Image Test of various Sizes:</h2>
Original: 968 x 674 <br />
<img src='@(getImageSrc("assets/images/4.png",200,200))' /> 200 x 200
<img src='@(getImageSrc("assets/images/4.png",100,100))' /> 100 x 100
<img src='@(getImageSrc("assets/images/4.png",200,100))' /> 200 x 100
<img src='@(getImageSrc("assets/images/4.png",100,200))' /> 100 x 200

<br />
<br />
<br />
<br />

Original: 200 x 200 <br />

<img src='@(getImageSrc("assets/images/0.jpg",200,200))' />
<img src='@(getImageSrc("assets/images/0.jpg",100,100))' />
<img src='@(getImageSrc("assets/images/0.jpg",100,200))' />
<img src='@(getImageSrc("assets/images/0.jpg",200,100))' />

<br />

<img src='@(getImageSrc("assets/images/0.jpg",300,300))' />
<img src='@(getImageSrc("assets/images/0.jpg",100,100))' />
<img src='@(getImageSrc("assets/images/0.jpg",100,300))' />
<img src='@(getImageSrc("assets/images/0.jpg",300,100))' />

<br />
<img src='@(getImageSrc("assets/images/0.jpg",50,50))' />
<img src='@(getImageSrc("assets/images/0.jpg",100,100))' />
<img src='@(getImageSrc("assets/images/0.jpg",100,50))' />
<img src='@(getImageSrc("assets/images/0.jpg",50,100))' />


<br />
<img src='@(getImageSrc("assets/images/0.jpg",50,400))' />
<img src='@(getImageSrc("assets/images/0.jpg",500,400))' />
<img src='@(getImageSrc("assets/images/0.jpg",10,50))' />
<img src='@(getImageSrc("assets/images/0.jpg",10,100))' />


@endblock



@block('script')

<script>
    var apple = 21;
    console.log(apple);

    function f(man, woman, child){
        var res = man + woman + child;
        return res;
    }

    f(2,3,4)

</script>

<script>
    var apple = 23;
    console.log(apple);
</script>

@endblock