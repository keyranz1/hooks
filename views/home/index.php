@extend('_layout')


@block('style')
<style>

    html, body{
        min-height: 100%;
        position: relative;
    }
    #cover, #page-2{
        position: relative;
        height: 98%;
        width: 98%;
        border: 1px solid #4d6e78;
        text-align: center;
        margin: 1% 1%;
        box-sizing: border-box;
        background: #4d6e78;
        color: #FFF;
    }
    #cover h3,
    #cover h1,
    #cover a
    {
        color: #FFF;
    }
    #page-2{
        background:  #f2f2f2;
        color: #4d6e78;
        text-align: left;
        padding: 20px;
    }
    .vertical-center{
        position: relative;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }

</style>
@endblock


@block('content')

    <div id="cover">
        <div class="vertical-center">
            <h3>Hi, there, you have landed!</h3>
            <h1>Welcome, to @( $app.name ) by Hooks.</h1>
            <a href="#page-2">Read more</a>
        </div>
    </div>


    <div id="page-2">
        <h1>Some Facts</h1>
            <ul>
                <li>This page is loaded from: home/index view on views folder. The corresponding controller is HomeController/Index().</li>
                <li>You can change views by passing filename in view helper class view("somewhere/else") in each controller class.
                    It loads views/somewhere/else.php
                </li>
            </ul>
    </div>


@endblock



@block('script')

<script>
    var apple = 1;

    function add( a  , b){
        return (a + b);
    }
    console.log(add(apple , 5));
</script>

@endblock