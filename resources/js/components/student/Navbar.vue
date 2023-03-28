<template>
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/store/index">
            <img src="public/assets/img/header_logo.png" height="75" alt="OB Montessori">
        </a>
        <a class="nav-item" href="/store/cart"><i class="el-icon-shopping-cart-2"></i></a>
        <a class="nav-item" href="/store/transaction_history"><i class="el-icon-s-claim"></i></a>
        <a class="nav-item" @click="$root.logout()"><i class="fa fa-sign-out"></i></a>
    </nav>
</template>
