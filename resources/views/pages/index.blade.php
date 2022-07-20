<!-- PRODUCT GRID -->
@extends('layout.app')

@section('title', 'Home')

@section('css')
    <style>
        .pagination {
            display: inline-block;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        .text-truncate-custom {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .text-truncate-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-2 col-sm-12 mt-3">
            <h5>Kategori</h5>
            <div class="card">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="/" class="text-decoration-none text-capitalize">All</a>
                    </li>
                    @foreach ($categoryData as $item)
                        @php
                            $route = str_replace(" ","-", $item->name);
                        @endphp
                        <li class="list-group-item">
                            <a href="/?category={{ $route }}" class="text-decoration-none text-capitalize">{{ $item->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-10 col-sm-12">
            @if ($menu)
                <?php
                    //Columns must be a factor of 12 (1,2,3,4,6,12)
                    $numOfCols = 3;
                    $rowCount = 0;
                    $bootstrapColWidth = 12 / $numOfCols;
                    foreach ($menu as $item){
                    if($rowCount % $numOfCols == 0) { ?> <div class="row mt-3"> <?php } 
                        $rowCount++; ?>
                        <div class="col-md-<?php echo $bootstrapColWidth; ?> col-sm-12">
                            <div class="card card-sm">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="#" class="btn btn-sm btn-warning">
                                                5
                                                <i class="fa-solid fa-star"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <img src="{{ $item->urlPhoto }}" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate">{{ $item->name }}</h5>
                                    <p class="text-truncate-custom text-justify" style="height: 75px;">
                                        {{ $item->description }}
                                    </p>
                                    <p class="font-weight-bold">
                                        @currency($item->price)
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-2">
                                            <form action="{{ route('cart.add-to-cart') }}" method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $item->name }}" name="menuName">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa-solid fa-cart-shopping"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-2">
                                            <a href="#" class="btn btn-sm btn-danger">
                                                <i class="fa-solid fa-heart"></i>
                                            </a>
                                        </div>
                                        <div class="col-8 text-truncate text-capitalize" style="text-align: right">
                                            {{ $item->Category->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    $page = 'not full';
                    if($rowCount % $numOfCols == 0) { $page = 'full'; ?>
                </div> <?php } } ?>
            @else
            <div class="row mt-3">
                <div class="col-12">
                    <h3>Tidak ada menu.</h1>
                </div>
            </div>
            @endif
        </div>
        @if ($menu && $page == 'not full')
            <div class="row mt-3">
                <div class="col-12">
                    <div class="pagination">
                        <a href="{{ $pagination->previousPageURL }}">&laquo;</a>
                        <?php
                            for($i = 1; $i <= $pagination->totalPages; $i++) {
                                $active = '';
                                if ($i == $pagination->currentPage) {
                                    $active = 'active';
                                }
                        ?>
                        <a href="/?page={{ $i }}{{ Request::has('category') ? $pagination->categoryURL : '' }}"
                            class="{{ $active }}">{{ $i }}</a>
                        <?php
                            }
                        ?>
                        <a href="{{ $pagination->nextPageURL }}">&raquo;</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @if ($menu && $page == 'full')
        <div class="row mt-3">
            <div class="col-lg-2"></div>
            <div class="col-lg-10 col-sm-12">
                <div class="pagination">
                    <a href="{{ $pagination->previousPageURL }}">&laquo;</a>
                    <?php
                        for($i = 1; $i <= $pagination->totalPages; $i++) {
                            $active = '';
                            if ($i == $pagination->currentPage) {
                                $active = 'active';
                            }
                    ?>
                    <a href="/?page={{ $i }}{{ Request::has('category') ? $pagination->categoryURL : '' }}"
                        class="{{ $active }}">{{ $i }}</a>
                    <?php
                        }
                    ?>
                    <a href="{{ $pagination->nextPageURL }}">&raquo;</a>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('js')
    @if (Session::has('success'))
        <script>
            Swal.fire("{{ Session::get('success') }}", "{{ Session::get('status') }}", "success");
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