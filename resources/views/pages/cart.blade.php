@extends('layout.app')

@section('css')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-3vy4BJZCS1KVIJMp"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
@endsection

@section('content')
    <h3>My Cart</h3>
    @if ($cart)
        <div class="row pb-5">
            <div class="col-lg-8 col-sm-12">
                <form action="{{ route('cart.checkout') }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="result_type" id="result-type" value="">
                    <input type="hidden" name="result_data" id="result-data" value="">
                    @foreach ($cart as $item)
                        <div class="card mt-3">
                            <div class="row no-gutters">
                                <div class="col-md-4 col-sm-12">
                                    <img class="img-fluid" width="100%" src="{{ $item->Menu->urlPhoto }}"
                                        alt="{{ $item->Menu->name }}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title menu-name">{{ $item->Menu->name }}</h5>
                                        <p class="card-text">
                                            {{ $item->Menu->description }}
                                        </p>
                                        <p class="card-text price">
                                            @currency($item->Menu->price)
                                        </p>

                                        <div class="quantity" style="display: flex; gap: 8px; margin-top: 10px">
                                            <input type="hidden" class="price-int" name="price[]"
                                                value="{{ $item->Menu->price }}">
                                            <input type="hidden" class="menu-id" name="menuId[]"
                                                value="{{ $item->Menu->id }}">

                                            <input type="button" value="-" class="qtyminus minus" field="quantity"
                                                style="width: 35px;" />
                                            <input type="text" data-cart="{{ $item->id }}"
                                                value="{{ $item->quantity }}" min="1" max="3"
                                                name="quantity[]" class="qty" style="text-align: center; width: 35px;" />
                                            <input type="button" value="+" class="qtyplus plus" field="quantity"
                                                style="width: 35px;" />
                                            <a href="#" data-cart="{{ $item->id }}"
                                                class="btn btn-sm btn-danger removeCart">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="col-lg-4 col-sm-12 mt-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header font-weight-bold">
                                Payment
                            </div>
                            <ul class="list-group list-group-flush totals">
                                <li class="list-group-item">
                                    <div class="row justify-content-between">
                                        <div class="col-6">
                                            Subtotal
                                        </div>
                                        <div class="col-6 totals-value" id="cart-subtotal"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row justify-content-between totals-item">
                                        <div class="col-6">
                                            Tax (10%)
                                        </div>
                                        <div class="col-6 totals-value" id="cart-tax"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row justify-content-between totals-item">
                                        <div class="col-6">
                                            Service Charge (3%)
                                        </div>
                                        <div class="col-6 totals-value" id="cart-service-charge"></div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row justify-content-between totals-item">
                                        <div class="col-6">
                                            Takeaway Charge (2%)
                                        </div>
                                        <input type="hidden" class="cart-takeaway-charge-input" name="" id="cart-takeaway-charge-input">
                                        <div class="col-6 totals-value" id="cart-takeaway-charge">@currency(0)</div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row justify-content-between totals-item">
                                        <div class="col-6">
                                            Grand Total
                                        </div>
                                        <div class="col-6 totals-value" id="cart-total"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <select name="" id="transaction-type" class="form-control">
                            <option value="0">Dine In</option>
                            <option value="1">Takeaway</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button id="pay-button" type="submit" class="btn btn-primary btn-block" id="pay-button">
                            Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
    <h5>Your cart is empty.</h5>
@endif
@endsection


@section('js')
    <script>
        $(document).ready(($) => {
            $('.quantity').on('click', '.plus', function(e) {
                let $input = $(this).prev('input.qty');
                let val = parseInt($input.val());
                $input.val(val + 1).change();
            })

            $('.quantity').on('click', '.minus', function(e) {
                let $input = $(this).next('input.qty');
                var val = parseInt($input.val());
                if (val > 1) {
                    $input.val(val - 1).change();
                }
            })

            $('.qty').on('change', function(e) {
                let quantity = $(this).val()
                let cartId = $(this).attr('data-cart')
                let formData = {
                    quantity: quantity,
                    cartId: cartId
                }

                $.ajax({
                    url: `{{ $url }}cart/${cartId}`,
                    type: "PATCH",
                    dataType: "json",
                    data: formData,
                    success: function(data) {
                        location.reload()
                    },
                })
            })

            $('.removeCart').on('click', function(e) {
                let cartId = $(this).attr('data-cart')
                $.ajax({
                    url: `{{ $url }}cart/${cartId}`,
                    type: "DELETE",
                    dataType: "json",
                    success: function(data) {
                        location.reload()
                    },
                })
            })

            function changeResult(type, data) {
                $("#result-type").val(type);
                $("#result-data").val(JSON.stringify(data));
            }

            function getFormatRupiah(angka) {
                let number_string = angka.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                let result = `Rp. ${rupiah}`

                return result;
            }

            function getSubTotal() {
                let quantity = []
                $('.qty').each(function() {
                    quantity.push($(this).val())
                })

                let price = []
                $('.price-int').each(function() {
                    price.push($(this).val())
                })

                let result = 0
                quantity.forEach((value, index) => {
                    result += price[index] * value
                })

                return result
            }

            function getTax(subTotal, taxPercentage) {
                let tax = subTotal * taxPercentage / 100
                return tax
            }

            let subTotal = getSubTotal()
            let menuTax = getTax(subTotal, 10)
            let serviceTax = getTax(subTotal, 3)
            let takeawayCharge = getTax(subTotal, 2)
            let grandTotal = subTotal + menuTax + serviceTax
            $('#transaction-type').on('change', function(e) {
                let type = this.value
                $('#cart-takeaway-charge-input').val(type)

                if(type == 1) {
                    let newGrandTotal = grandTotal + takeawayCharge
                    $('#cart-total').text(getFormatRupiah(newGrandTotal))
                    $('#cart-takeaway-charge').text(getFormatRupiah(takeawayCharge))
                } else {
                    $('#cart-total').text(getFormatRupiah(grandTotal))
                    $('#cart-takeaway-charge').text(getFormatRupiah(0))
                }
            })

            $('#cart-subtotal').text(getFormatRupiah(subTotal))
            $('#cart-tax').text(getFormatRupiah(menuTax))
            $('#cart-service-charge').text(getFormatRupiah(serviceTax))
            $('#cart-total').text(getFormatRupiah(grandTotal))

            const payButton = document.getElementById('pay-button');

            payButton.addEventListener('click', function(event) {
                event.preventDefault();
                let menuName = []
                let cartId = []
                let quantity = []
                let price = []

                $('.menu-name').each(function() {
                    menuName.push($(this).text())
                })

                $('.menu-id').each(function() {
                    cartId.push($(this).val())
                })

                $('.qty').each(function() {
                    quantity.push($(this).val())
                })

                $('.price-int').each(function() {
                    price.push($(this).val())
                })

                let items = {}
                menuName.forEach((value, index) => {
                    items[index] = {
                        id: cartId[index],
                        name: value,
                        price: parseInt(price[index]),
                        quantity: parseInt(quantity[index])
                    }
                })
                
                let takeawayInput = $('#cart-takeaway-charge-input').val() == 1 ? 2 : 0
                
                let subTotalAfterClick = getSubTotal()
                let takeawayChargeAfterClick = getTax(subTotal, takeawayInput)
                let grandTotalAfterClick = subTotal + menuTax + serviceTax + takeawayChargeAfterClick

                let formData = {
                    subTotal: subTotalAfterClick,
                    total: grandTotalAfterClick,
                    items: items,
                    tax: menuTax,
                    service: serviceTax,
                    takeawayCharge: takeawayChargeAfterClick,
                    UserId: "{{ Session::get('userId') }}"
                }

                $.ajax({
                    url: `{{ $url }}order`,
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function(data) {
                        let token = data.token
                        snap.pay(token, {
                            onSuccess: function(result) {
                                console.log(result)
                                changeResult('success', result);
                                console.log(result.status);
                                console.log(result.status_message);
                                $("#payment-form").submit();
                            },
                            onPending: function(result) {
                                console.log(result)
                                changeResult('pending', result);
                                console.log(result.status);
                                console.log(result.status_message);
                                $("#payment-form").submit();
                            },
                            onError: function(result) {
                                console.log(result)
                                changeResult('error', result);
                                console.log(result.status);
                                console.log(result.status_message);
                                $("#payment-form").submit();
                            }
                        })
                    },
                })
            })
        })
    </script>
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
