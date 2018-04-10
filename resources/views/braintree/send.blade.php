<html>
<head>
    <title>braintree</title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<div style="margin-top: 200px; text-align: center; font-size: 50px;">
    <i class="fa fa-spinner fa-pulse"></i>
</div>


<div style="display: none;">
    <form action="{{ Braintree_TransparentRedirect::url() }}" method="post">

        @foreach ($inputs as $name => $input)
            @if (!is_array($input))
                <input type="text" name="{{ $name }}" value="{{ $input }}">
            @else

                @foreach ($input as $name2 => $input2)
                    @if (!is_array($input2))
                        <input type="text" name="{{ $name }}[{{ $name2 }}]" value="{{ $input2 }}">
                    @else

                        @foreach ($input2 as $name3 => $input3)
                            <input type="text" name="{{ $name }}[{{ $name2 }}][{{ $name3 }}]" value="{{ $input3 }}">
                        @endforeach

                    @endif
                @endforeach

            @endif
        @endforeach




        {{-- for braintree --}}
        <?php $tr_data = Braintree_TransparentRedirect::transactionData([
                'redirectUrl' => route('braintree_callback'),
                'transaction' => array('amount' => round($total, 2), 'type' => 'sale')
        ]) ?>
        <input type="text" name="tr_data" value="<?php echo $tr_data ?>" />
    </form>
</div>

<script>

    $(function() {
        $('form').submit();
    });

</script>

</body>
</html>





