<!-- PRODUCT GRID -->
@extends('layout.app')

@section('title', 'My Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#payment" role="tab" aria-controls="payment"
                                aria-selected="true">Waiting for Payment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#queue" role="tab" aria-controls="queue" aria-selected="false">On
                                Process</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#ready" role="tab" aria-controls="ready"
                                aria-selected="false">Ready</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#history" role="tab" aria-controls="history"
                                aria-selected="false">History</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="payment" role="tabpanel">
                            @if ($order['pending'] != null)
                                @foreach ($order['pending'] as $item)
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <input type="hidden" id="expired-date" value="{{ $item->createdAt }}">
                                            @if ($item->paymentType == 'gopay' || $item->paymentType == 'shopeepay')
                                                <h5 class="text-capitalize">
                                                    {{ $item->paymentType }}
                                                </h5>
                                            @else
                                                <h5>
                                                    VA Number:
                                                    <span class="badge badge-primary">{{ $item->vaNumber }}</span>
                                                </h5>
                                            @endif
                                            {{ \Carbon\carbon::parse(strtotime($item->createdAt))->setTimezone('Asia/Jakarta')->translatedFormat('d M Y - H:i') }}
                                        </div>
                                        <div class="card-body">
                                            <div class="row justify-content-between">
                                                <div class="col-4">
                                                    <h6 class="card-title">{{ $item->orderCode }}</h6>
                                                    <p class="card-text">
                                                        @foreach ($item->OrderDetails as $detail)
                                                            {{ $detail->Menu->name }} x {{ $detail->quantity }} <br>
                                                        @endforeach
                                                    </p>
                                                </div>
                                                <div class="col-2">
                                                    <p id="demo" class="text-center"></p>
                                                    <img src="https://api.sandbox.midtrans.com/v2/gopay/{{ $item->transactionId }}/qr-code"
                                                        alt="QR Code" class="img-fluid" width="150">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer font-weight-bold">
                                            @currency($item->amount)
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3>No transaction found yet.</h3>
                            @endif
                        </div>

                        <div class="tab-pane" id="queue" role="tabpanel" aria-labelledby="queue-tab">
                            @if ($order['settlement'] != null)
                                @foreach ($order['settlement'] as $item)
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5>
                                                Order Queue:
                                                <span class="badge badge-primary">{{ $item->queue }}</span>
                                            </h5>
                                            {{ \Carbon\carbon::parse(strtotime($item->createdAt))->setTimezone('Asia/Jakarta')->translatedFormat('d M Y - H:i') }}
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $item->orderCode }}</h6>

                                            <p class="card-text">
                                                @foreach ($item->OrderDetails as $detail)
                                                    {{ $detail->Menu->name }} x {{ $detail->quantity }} <br>
                                                @endforeach
                                            </p>

                                            <h6 class="card-title">
                                                @currency($item->amount)
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3>No transaction found yet.</h3>
                            @endif
                        </div>

                        <div class="tab-pane" id="ready" role="tabpanel" aria-labelledby="ready-tab">
                            @if ($order['ready'] != null)
                                @foreach ($order['ready'] as $item)
                                <form action="{{ route('order.complete') }}" id="form-complete" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $item->transactionId }}" name="transactionId">
                                </form>
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5>
                                                Order Queue:
                                                <span class="badge badge-success">{{ $item->queue }}</span>
                                            </h5>
                                            {{ \Carbon\carbon::parse(strtotime($item->createdAt))->setTimezone('Asia/Jakarta')->translatedFormat('d M Y - H:i') }}
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $item->orderCode }}</h6>

                                            <p class="card-text">
                                                @foreach ($item->OrderDetails as $detail)
                                                    {{ $detail->Menu->name }} x {{ $detail->quantity }} <br>
                                                @endforeach
                                            </p>
                                            <h6 class="card-title">
                                                @currency($item->amount)
                                            </h6>
                                            <a href="#" class="btn btn-success" id="complete-btn">Complete the order</a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3>No transaction found yet.</h3>
                            @endif
                        </div>

                        <div class="tab-pane" id="history" role="tabpanel" aria-labelledby="history-tab">
                            @if ($order['history'] != null)
                                @foreach ($order['history'] as $item)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>{{ \Carbon\carbon::parse(strtotime($item->createdAt))->setTimezone('Asia/Jakarta')->translatedFormat('d M Y - H:i') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $item->orderCode }}</h6>

                                        <p class="card-text">
                                            @foreach ($item->OrderDetails as $detail)
                                                {{ $detail->Menu->name }} x {{ $detail->quantity }} <br>
                                            @endforeach
                                        </p>
                                        <h6 class="card-title">
                                            @currency($item->amount)
                                        </h6>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <h3>No transaction found yet.</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $('#bologna-list a').on('click', function(e) {
            e.preventDefault()
            $(this).tab('show')
        })

        
        // Set the date we're counting down to
        const expiredDate = $('#expired-date').val()
        let countDownDate = new Date(expiredDate)
        countDownDate = countDownDate.setMinutes(countDownDate.getMinutes() + 15)

        // Update the count down every 1 second
        let x = setInterval(function() {

            // Get today's date and time
            let now = new Date().getTime();

            // Find the distance between now and the count down date
            let distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
                minutes + "m " + seconds + "s ";

            // If the count down is over, write some text 
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "EXPIRED";
            }
        }, 1000);

        const btnComplete = document.getElementById('complete-btn')

        btnComplete.addEventListener('click', function(event) {
            Swal.fire({
                title: 'Do you want to complete the order?',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $("#form-complete").submit();
                }
            })
        })
    </script>

    @if (Session::has('success'))
        <script>
            Swal.fire("{{ Session::get('success') }}", "", "success");
        </script>
    @elseif (Session::has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ Session::get('error') }}",
            })
        </script>
    @endif
@endsection
