<template>
    <div>
        <div v-if="isAdmin">
            <aside class="menu-sidebar d-none d-lg-block">
                <div class="logo">
                    <h2>tatoo.ru</h2>
                </div>
                <div class="menu-sidebar__content js-scrollbar1">
                    <nav class="navbar-sidebar">
                        <ul class="list-unstyled navbar__list">
                            <li :class="{ 'active': menu.isActive.orders, 'has-sub': menu.isActive.orders}" @click="active('orders', 'menu')" v-if="isAdmin">
                                <router-link :to="'/admin/orders'">
                                    <i class="far fa-money-bill-alt"></i>
                                    Заказы
                                </router-link>
                            </li>
                            <li :class="{ 'active': menu.isActive.tatoos, 'has-sub': menu.isActive.tatoos}" @click="active('tatoos', 'menu')" v-if="isAdmin">
                                <router-link :to="'/admin/tatoos'">
                                    <i class="fab fa-gitkraken"></i>
                                    Татуировки
                                </router-link>
                            </li>
                            <li :class="{ 'active': menu.isActive.employees, 'has-sub': menu.isActive.employees}" @click="active('employees', 'menu')" v-if="isAdmin">
                                <router-link :to="'/admin/employees'">
                                    <i class="fas fa-user-tie"></i>
                                    Персонал
                                </router-link>
                            </li>
                            <li :class="{ 'active': menu.isActive.appointments, 'has-sub': menu.isActive.appointments}" @click="active('appointments', 'menu')" v-if="isAdmin">
                                <router-link :to="'/admin/appointments'">
                                    <i class="fas fa-trophy"></i>
                                    Должности
                                </router-link>
                            </li>
                            <li :class="{ 'active': menu.isActive.users, 'has-sub': menu.isActive.users}" @click="active('users', 'menu')" v-if="isAdmin">
                                <router-link :to="'/admin/users'">
                                    <i class="fas fa-users"></i>
                                    Пользователи
                                </router-link>
                            </li>
                            <li :class="{ 'active': menu.isActive.audit, 'has-sub': menu.isActive.audit}" @click="active('audit', 'menu')" v-if="isAdmin">
                                <router-link :to="'/admin/audit'">
                                    <i class="far fa-edit"></i>
                                    Аудит
                                </router-link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <div class="page-container">
                <!-- HEADER DESKTOP-->
                <header class="header-desktop" style="z-index:0">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">
                            <div class="header-wrap">
                                <!--<form class="form-header" action="" method="POST">-->
                                <!--<input class="au-input au-input&#45;&#45;xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />-->
                                <!--<button class="au-btn&#45;&#45;submit" type="submit">-->
                                <!--<i class="zmdi zmdi-search"></i>-->
                                <!--</button>-->
                                <!--</form>-->
                                <div class="header-button">
                                    <!--<div class="noti-wrap">-->
                                    <!--<div class="noti__item js-item-menu">-->
                                    <!--<i class="zmdi zmdi-comment-more"></i>-->
                                    <!--<span class="quantity">1</span>-->
                                    <!--<div class="mess-dropdown js-dropdown">-->
                                    <!--<div class="mess__title">-->
                                    <!--<p>You have 2 news message</p>-->
                                    <!--</div>-->
                                    <!--<div class="mess__item">-->
                                    <!--<div class="image img-cir img-40">-->
                                    <!--<img src="images/icon/avatar-06.jpg" alt="Michelle Moreno" />-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<h6>Michelle Moreno</h6>-->
                                    <!--<p>Have sent a photo</p>-->
                                    <!--<span class="time">3 min ago</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="mess__item">-->
                                    <!--<div class="image img-cir img-40">-->
                                    <!--<img src="images/icon/avatar-04.jpg" alt="Diane Myers" />-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<h6>Diane Myers</h6>-->
                                    <!--<p>You are now connected on message</p>-->
                                    <!--<span class="time">Yesterday</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="mess__footer">-->
                                    <!--<a href="#">View all messages</a>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="noti__item js-item-menu">-->
                                    <!--<i class="zmdi zmdi-email"></i>-->
                                    <!--<span class="quantity">1</span>-->
                                    <!--<div class="email-dropdown js-dropdown">-->
                                    <!--<div class="email__title">-->
                                    <!--<p>You have 3 New Emails</p>-->
                                    <!--</div>-->
                                    <!--<div class="email__item">-->
                                    <!--<div class="image img-cir img-40">-->
                                    <!--<img src="images/icon/avatar-06.jpg" alt="Cynthia Harvey" />-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<p>Meeting about new dashboard...</p>-->
                                    <!--<span>Cynthia Harvey, 3 min ago</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="email__item">-->
                                    <!--<div class="image img-cir img-40">-->
                                    <!--<img src="images/icon/avatar-05.jpg" alt="Cynthia Harvey" />-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<p>Meeting about new dashboard...</p>-->
                                    <!--<span>Cynthia Harvey, Yesterday</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="email__item">-->
                                    <!--<div class="image img-cir img-40">-->
                                    <!--<img src="images/icon/avatar-04.jpg" alt="Cynthia Harvey" />-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<p>Meeting about new dashboard...</p>-->
                                    <!--<span>Cynthia Harvey, April 12,,2018</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="email__footer">-->
                                    <!--<a href="#">See all emails</a>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="noti__item js-item-menu">-->
                                    <!--<i class="zmdi zmdi-notifications"></i>-->
                                    <!--<span class="quantity">3</span>-->
                                    <!--<div class="notifi-dropdown js-dropdown">-->
                                    <!--<div class="notifi__title">-->
                                    <!--<p>You have 3 Notifications</p>-->
                                    <!--</div>-->
                                    <!--<div class="notifi__item">-->
                                    <!--<div class="bg-c1 img-cir img-40">-->
                                    <!--<i class="zmdi zmdi-email-open"></i>-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<p>You got a email notification</p>-->
                                    <!--<span class="date">April 12, 2018 06:50</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="notifi__item">-->
                                    <!--<div class="bg-c2 img-cir img-40">-->
                                    <!--<i class="zmdi zmdi-account-box"></i>-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<p>Your account has been blocked</p>-->
                                    <!--<span class="date">April 12, 2018 06:50</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="notifi__item">-->
                                    <!--<div class="bg-c3 img-cir img-40">-->
                                    <!--<i class="zmdi zmdi-file-text"></i>-->
                                    <!--</div>-->
                                    <!--<div class="content">-->
                                    <!--<p>You got a new file</p>-->
                                    <!--<span class="date">April 12, 2018 06:50</span>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="notifi__footer">-->
                                    <!--<a href="#">All notifications</a>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <div class="account-wrap">
                                        <div class="account-item clearfix js-item-menu">
                                            <!--<div class="image">-->
                                            <!--<img src="images/icon/avatar-01.jpg" alt="John Doe" />-->
                                            <!--</div>-->
                                            <div class="content" v-if="Object.keys(user).length">
                                                <a class="js-acc-btn" href="#">
                                                    {{ user.full_name }}
                                                    <i class="far fa-user"></i>
                                                </a>
                                                <a href="#" @click="logout()">
                                                    Выйти
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </a>
                                            </div>
                                            <!--<div class="account-dropdown js-dropdown">-->
                                            <!--<div class="info clearfix">-->
                                            <!--<div class="image">-->
                                            <!--<a href="#">-->
                                            <!--<img src="images/icon/avatar-01.jpg" alt="John Doe" />-->
                                            <!--</a>-->
                                            <!--</div>-->
                                            <!--<div class="content">-->
                                            <!--<h5 class="name">-->
                                            <!--<a href="#">john doe</a>-->
                                            <!--</h5>-->
                                            <!--<span class="email">johndoe@example.com</span>-->
                                            <!--</div>-->
                                            <!--</div>-->
                                            <!--<div class="account-dropdown__body">-->
                                            <!--<div class="account-dropdown__item">-->
                                            <!--<a href="#">-->
                                            <!--<i class="zmdi zmdi-account"></i>Account</a>-->
                                            <!--</div>-->
                                            <!--<div class="account-dropdown__item">-->
                                            <!--<a href="#">-->
                                            <!--<i class="zmdi zmdi-settings"></i>Setting</a>-->
                                            <!--</div>-->
                                            <!--<div class="account-dropdown__item">-->
                                            <!--<a href="#">-->
                                            <!--<i class="zmdi zmdi-money-box"></i>Billing</a>-->
                                            <!--</div>-->
                                            <!--</div>-->
                                            <!--<div class="account-dropdown__footer">-->
                                            <!--<a href="#">-->
                                            <!--<i class="zmdi zmdi-power"></i>Logout</a>-->
                                            <!--</div>-->
                                            <!--</div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- END MENU SIDEBAR-->

                <!-- PAGE CONTAINER-->
                <div class="main-content">
                    <div class="section__content section__content--p30">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="isClient || $route.name === 'login'">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

  export default {
    name: "admin",
    data() {
      return {
        menu: {
          isActive: {},
        },
        submenu: {
          isActive: {}
        },
      }
    },
    computed: {

        ...mapGetters('Auth', {
          'user': 'getUser',
          'token': 'getToken',
        }),

      isAdmin() {
        return this.user.role === 'admin' && this.token.length && this.user.csrf_token.length;
      },

      isClient() {
        return this.user.role === 'client' && this.token.length;
      }
    },
    methods: {
      active(link, type) {
        this[type].isActive = {};
        this[type].isActive[link] = true;
      },

      checkOpenTab() {
        if (this.$route.name !== 'login') {
          this.active(this.$route.name, 'menu');
        }
      },

      async logout() {
        const response = await axios.post('/logout');
        if (response.status !== 200 || response.data.status === 'error') {
          console.log(response);
          this.$swal('Ошибка!', response.data, 'error');
          return false;
        }
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('csrf_token');
        this.setUser({});
        this.$router.push({ name: 'login' });
      },

        ...mapActions('Auth', {
          'setUser': 'setUser'
        }),

      verifyUser() {
        return (typeof this.user === 'object' && this.user.csrf_token !== undefined);
      }
    },

    created() {
      if (! this.verifyUser()) {
        this.$router.push({ name: 'login'});
        return false;
      }
      this.checkOpenTab();
    }
  }
</script>

<style scoped>
    a::after {
        content: none !important;
    }
</style>
