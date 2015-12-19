<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@($page.title)</title>
        <meta name="keywords" content="@($page.keywords)" />
        <meta name="description" content="@($page.metaContent)">
        <meta name="author" content="@($page.author)">
        <base href="https://local.akitech.org/sites/hooks/">

        <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
        <link rel="stylesheet" href="assets/stylesheets/default.css">

        @block('style')
            <!-- For Extra Set of Styles on child page -->
        @endblock

    </head>

    <body>


        @block('content')

        <!-- Content from child page is rendered here. You may create multiple blocks
        with other names like [@]block('sidebar') etc if you like ;) -->

        @endblock


    </body>


    <script src="assets/library/jquery-2.1.4.min.js"></script>
    <script src="assets/javascripts/default.js"></script>

    @block('script')
        <!-- For Extra Set of Scripts on child page -->
    @endblock

</html>