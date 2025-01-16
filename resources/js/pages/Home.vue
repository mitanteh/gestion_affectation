<template>
    <div>
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xxl-12 col-lg-6 order-first">
                        <div class="row row-cols-xxl-4 row-cols-1">
                            <div class="col">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-secondary opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Total Projets</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="0">0</span></h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-secondary-subtle text-secondary rounded fs-3">
                                                    <i class="ph-wallet"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                    
                            <div class="col">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-info opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Orders</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="65802">0</span> </h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                                    <i class="ph-storefront"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                    
                            <div class="col">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-warning opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Ressources</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="79958">0</span> </h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                                    <i class="ph-user-circle"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="vr rounded bg-primary opacity-50" style="width: 4px;"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted fs-14 text-truncate">Products</p>
                                                <h4 class="fs-22 fw-semibold mb-3"><span class="counter-value" data-target="36758">0</span> </h4>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                                    <i class="ph-sketch-logo"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div> <!-- end row-->
                    </div>
                    
                </div>

                <div class="row widget-responsive-fullscreen">
                    <div class="col-xxl-3">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Customer Satisfaction</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Download Report</a>
                                            <a class="dropdown-item" href="#">Export</a>
                                            <a class="dropdown-item" href="#">Import</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->
                    
                            <div class="card-body">
                                <div id="area_chart_spline" data-colors='["--tb-primary", "--tb-success"]' class="apex-charts" dir="ltr"></div>
                    
                                <div class="mt-3 p-3 border rounded text-center">
                                    <div class="row">
                                        <div class="col-xl-6 border-end">
                                            <h6 class="fs-17">$10,532</h6>
                                            <p class="text-muted mb-0"><i class="bi bi-app-indicator text-primary align-middle me-1"></i> This Month</p>
                                        </div>
                                        <div class="col-xl-6">
                                            <h6 class="fs-17">$9,532</h6>
                                            <p class="text-muted mb-0"><i class="bi bi-app-indicator text-success align-middle me-1"></i> Last Month</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                    <div class="col-xxl-9">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Stock Report</h4>
                                <div class="flex-shrink-0">
                                    <a href="statistics.html" class="btn btn-soft-info btn-sm">
                                        <i class="ri-file-list-3-line align-middle"></i> Generate Report
                                    </a>
                                </div>
                            </div><!-- end card header -->
                    
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                        <thead class="text-muted table-light">
                                            <tr>
                                                <th scope="col">Product ID</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Updated Date</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Stock Status</th>
                                                <th scope="col">Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="product-overview.html" class="fw-medium link-primary">#00541</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="../assets/images/products/img-1.png" alt="" class="avatar-xs rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1"><a href="product-overview.html" class="text-reset">Rockerz Ear Bluetooth Headphones</a></div>
                                                    </div>
                                                </td>
                                                <td>16 Aug, 2022</td>
                                                <td>
                                                    <span class="text-secondary">$658.00</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success ">In Stock</span>
                                                </td>
                                                <td>15 PCS</td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="product-overview.html" class="fw-medium link-primary">#07484</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="../assets/images/products/img-5.png" alt="" class="avatar-xs rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1"><a href="product-overview.html" class="text-reset">United Colors Of Benetton</a></div>
                                                    </div>
                                                </td>
                                                <td>05 Sep, 2022</td>
                                                <td>
                                                    <span class="text-secondary">$145.00</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning-subtle text-warning ">Low Stock</span>
                                                </td>
                                                <td>05 PCS</td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="product-overview.html" class="fw-medium link-primary">#01641</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="../assets/images/products/img-4.png" alt="" class="avatar-xs rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1"><a href="product-overview.html" class="text-reset">Striped Baseball Cap</a></div>
                                                    </div>
                                                </td>
                                                <td>28 Sep, 2022</td>
                                                <td>
                                                    <span class="text-secondary">$215.00</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger-subtle text-danger ">Out of Stock</span>
                                                </td>
                                                <td>0 PCS</td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="product-overview.html" class="fw-medium link-primary">#00065</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="../assets/images/products/img-3.png" alt="" class="avatar-xs rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1"><a href="product-overview.html" class="text-reset">350 ml Glass Grocery Container</a></div>
                                                    </div>
                                                </td>
                                                <td>02 Oct, 2022</td>
                                                <td>
                                                    <span class="text-secondary">$79.99</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success ">In Stock</span>
                                                </td>
                                                <td>37 PCS</td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="product-overview.html" class="fw-medium link-primary">#00156</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="../assets/images/products/img-2.png" alt="" class="avatar-xs rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1"><a href="product-overview.html" class="text-reset">One Seater Sofa</a></div>
                                                    </div>
                                                </td>
                                                <td>11 Oct, 2022</td>
                                                <td>
                                                    <span class="text-secondary">$264.99</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success ">In Stock</span>
                                                </td>
                                                <td>23 PCS</td>
                                            </tr><!-- end tr -->
                                            <tr>
                                                <td>
                                                    <a href="product-overview.html" class="fw-medium link-primary">#09102</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="../assets/images/products/img-8.png" alt="" class="avatar-xs rounded-circle">
                                                        </div>
                                                        <div class="flex-grow-1"><a href="product-overview.html" class="text-reset">Men's Running Shoes Active Grip</a></div>
                                                    </div>
                                                </td>
                                                <td>19 Nov, 2022</td>
                                                <td>
                                                    <span class="text-secondary">$264.99</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning-subtle text-warning ">Low Stock</span>
                                                </td>
                                                <td>23 PCS</td>
                                            </tr><!-- end tr -->
                                        </tbody><!-- end tbody -->
                                    </table><!-- end table -->
                                </div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div>

            </div>
        </div>
    </div>
</template>