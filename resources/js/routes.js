import Users from './components/admin/users/users';
import UserForm from './components/admin/users/user_form';

import Appointments from './components/admin/appointments/appointments';
import AppointmentForm from './components/admin/appointments/appointment_form';

import Tatoos from './components/admin/tatoos/tatoos';
import TatooForm from './components/admin/tatoos/tatoo_form';

import Employees from './components/admin/employees/employees';
import EmployeeForm from './components/admin/employees/employee_form';

import Orders from './components/admin/orders/orders';
import OrderForm from './components/admin/orders/order_form';

import Audit from './components/admin/audit/audit';

import Contacts from './components/public/content/contacts';
import PublicTatoos from './components/public/content/tatoos_public';
import PublicMasters from './components/public/content/masters_public';

import Login from './components/templates/admin/login';

const isAdmin = (to, from, next) => {
  let user = localStorage.getItem('user');
  user = (user !== undefined) ? JSON.parse(user) : '';
  (user.role === 'admin') ? next() : next('/login');
};

export const routes = [
  {
    name: 'users',
    path: '/admin/users',
    component: Users,
    beforeEnter: isAdmin
  },
  {
    name: 'user_form',
    path: '/admin/users/create',
    component: UserForm,
    beforeEnter: isAdmin
  },
  {
    name: 'user_form',
    path: '/admin/users/:id',
    component: UserForm,
    beforeEnter: isAdmin
  },
  {
    name: 'tatoos',
    path: '/admin/tatoos',
    component: Tatoos,
    beforeEnter: isAdmin
  },
  {
    name: 'tatoo_form',
    path: '/admin/tatoos/create',
    component: TatooForm,
    beforeEnter: isAdmin
  },
  {
    name: 'tatoo_form',
    path: '/admin/tatoos/:id',
    component: TatooForm,
    beforeEnter: isAdmin
  },
  {
    name: 'employees',
    path: '/admin/employees',
    component: Employees,
    beforeEnter: isAdmin
  },
  {
    name: 'employee_form',
    path: '/admin/employees/create',
    component: EmployeeForm,
    beforeEnter: isAdmin
  },
  {
    name: 'employee_form',
    path: '/admin/employees/:id',
    component: EmployeeForm,
    beforeEnter: isAdmin
  },
  {
    name: 'orders',
    path: '/admin/orders',
    component: Orders,
    beforeEnter: isAdmin
  },
  {
    name: 'order_form',
    path: '/admin/orders/create',
    component: OrderForm,
    beforeEnter: isAdmin
  },
  {
    name: 'order_form',
    path: '/admin/orders/:id',
    component: OrderForm,
    beforeEnter: isAdmin
  },
  {
    name: 'appointments',
    path: '/admin/appointments',
    component: Appointments,
    beforeEnter: isAdmin
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/create',
    component: AppointmentForm,
    beforeEnter: isAdmin
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/:id',
    component: AppointmentForm,
    beforeEnter: isAdmin
  },
  {
    name: 'audit',
    path: '/admin/audit',
    component: Audit,
    beforeEnter: isAdmin
  },

  /***
   *
   * Public routes
   *
   */
  {
    name: 'contacts',
    path: '/contacts',
    component: Contacts
  },
  {
    name: 'public_tatoos',
    path: '/tatoos',
    component: PublicTatoos
  },
  {
    name: 'public_masters',
    path: '/masters',
    component: PublicMasters
  },
  {
    name: 'login',
    path: '/login',
    component: Login
  }
];