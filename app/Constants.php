<?php

use App\Models\Customer;
use App\Models\CustomerHistory;
use App\Models\Product;
use App\Models\User;

const RECORDS_PER_PAGE = 50;
const SUCCESS = 'success';
const ERROR = 'error';
const WARN = 'warning';
const INFO = 'info';

const YES = 1;
const NO = 0;

const USER_ADMIN    = User::ADMIN;
const USER_EMPLOYEE = User::EMPLOYEE;

// const PRODUCT_TYPES = Product::TYPES;

const CUSTOMER_PENDING = Customer::PENDING;
const CUSTOMER_APPROVED = Customer::APPROVED;
const CUSTOMER_REJECTED = Customer::REJECTED;
const CUSTOMER_ACCEPTED = CustomerHistory::ACCEPTED;


const DATE_FORMAT = 'd-M-Y';
const PRINT_DATE_FORMAT = 'd-m-Y';

const BILLING_DAILY = Customer::DAILY;
const BILLING_MONTHLY = Customer::MONTHLY;

const PRODUCT_WATER = Product::WATER;
const PRODUCT_DISPENSER = Product::DISPENSER;

// upload paths
const PROFILE_PHOTO_PATH = '/upload/profilePhoto/';

// cache keys
const CACHE_GENERAL_SETTINGS = 'general_settings';
