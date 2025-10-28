<div class="card">
    <div class="card-body">
        <form action="{{route('admin.customer.search')}}" id="customer_search_form">
            <div class="row justify-content-center">
                <div class="col-md-6 d-flex">
                    <input type="search" name="keyword" class="form-control w-100" placeholder="Search customer">
                    <button class="btn btn-danger px-3" form="customer_search_form">Search</button>
                    <a href="{{route('admin.customer.create')}}" class="btn btn-primary ml-3 px-4">Add Customer</a>
                </div>
            </div>
        </form>
    </div>

    @include('includes.customer-modals')
</div>
