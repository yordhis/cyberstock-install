@extends('layouts.app')

@php
    $menuSuperior = [];
@endphp
@section('title', 'Cyber Stock')



@section('content')
    <style>
            .bg-preload {
            position: relative;
            background-color: #ced4da;
            overflow: hidden;
            }

            .preloadFalse {
            opacity:0 !important;
            height:0 !important;
            }

            .into-preload {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 50%;
            z-index: 1;
            width: 500%;
            margin-left: -250%;
            -webkit-animation: bgAnimation 0.8s linear infinite;
            animation: bgAnimation 0.8s linear infinite;
            background: linear-gradient(
                90deg,
                hsla(0, 0%, 100%, 0) 46%,
                hsla(0, 0%, 100%, 0.35) 50%,
                hsla(0, 0%, 100%, 0) 54%
                )
                50% 50%;
            }

            @keyframes bgAnimation {
            0% {
                transform: translate3d(-30%, 0, 0);
            }
            to {
                transform: translate3d(30%, 0, 0);
            }
            }
    </style>

        <div class="container-fluid bg-light">

            <div class="container p-5">
        
            
        
            <div class="row row-cols-1 row-cols-sm-2 ">
        
                <div class="col p-3 bg-white">
        
                <div class="card border-0">
                    <img class="card-img-top" src="https://www.w3schools.com/bootstrap5/img_avatar1.png" alt="Card image">
                    <div class="card-body">
        
                    <h4 class="card-title text-capitalize">Lorem ipsum dolor</h4>
        
                    <div class="d-flex border-0 justify-content-center">
                        <img src="https://www.w3schools.com/bootstrap5/img_avatar1.png" class="rounded-circle mx-3 my-3" style="width:60px; height:60px">
        
                        <p class="my-3 lead">Blanditiis ratione quod.</p>
        
                    </div>
        
                    <p class="card-text">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Molestiae ut sequi tempora eum eius nam optio dignissimos cupiditate temporibus, omnis sapiente quos distinctio corporis ad numquam explicabo at laudantium mollitia?
                    </p>
        
                    <div class="d-flex flex-row-reverse ">
                        <a href="#" class="btn btn-primary">See Profile</a>
                    </div>
        
                    </div>
                </div>
        
                </div>
        
                <div class="col p-3 bg-white">
        
                <div class="card border-0">
                    <img class="card-img-top" src="https://www.w3schools.com/bootstrap5/img_avatar1.png" alt="Card image">
                    <div class="card-body">
        
                    <h4 class="card-title text-capitalize">consectetur adipi</h4>
        
                    <div class="d-flex border-0 justify-content-center">
                        <img src="https://www.w3schools.com/bootstrap5/img_avatar1.png" class="rounded-circle mx-3 my-3" style="width:60px; height:60px">
        
                        <p class="my-3 lead">Sed maxime mollitia.</p>
        
                    </div>
        
                    <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed maxime mollitia harum doloribus libero sunt atque amet maiores officia unde architecto soluta voluptatem, quaerat cumque laudantium? Autem voluptatum sint cumque.
                    </p>
        
                    <div class="d-flex flex-row-reverse ">
                        <a href="#" class="btn btn-primary">See Profile</a>
                    </div>
        
                    </div>
                </div>
        
                </div>
        
            </div>
        
            </div>
        </div>
    
    
    
@endsection

