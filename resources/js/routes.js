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

import Contacts from './components/public/content/contacts';
import PublicTatoos from './components/public/content/tatoos_public';
import PublicMasters from './components/public/content/masters_public';

export const routes = [
  {
    name: 'users',
    path: '/admin/users',
    component: Users
  },
  {
    name: 'user_form',
    path: '/admin/users/create',
    component: UserForm
  },
  {
    name: 'user_form',
    path: '/admin/users/:id',
    component: UserForm
  },
  {
    name: 'tatoos',
    path: '/admin/tatoos',
    component: Tatoos
  },
  {
    name: 'tatoo_form',
    path: '/admin/tatoos/create',
    component: TatooForm
  },
  {
    name: 'tatoo_form',
    path: '/admin/tatoos/:id',
    component: TatooForm
  },
  {
    name: 'employees',
    path: '/admin/employees',
    component: Employees
  },
  {
    name: 'employee_form',
    path: '/admin/employees/create',
    component: EmployeeForm
  },
  {
    name: 'employee_form',
    path: '/admin/employees/:id',
    component: EmployeeForm
  },
  {
    name: 'orders',
    path: '/admin/orders',
    component: Orders
  },
  {
    name: 'order_form',
    path: '/admin/orders/create',
    component: OrderForm
  },
  {
    name: 'order_form',
    path: '/admin/orders/:id',
    component: OrderForm
  },
  {
    name: 'appointments',
    path: '/admin/appointments',
    component: Appointments
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/create',
    component: AppointmentForm
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/:id',
    component: AppointmentForm
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
];