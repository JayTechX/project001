<?php $this->load->view('landing/resources/head_base'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
<style type="text/css">
	.feature-attribute:hover {
		cursor: pointer;
	}

	.carrito-checkbox {
		display: block;
		position: relative;
		padding-left: 25px;
		margin-bottom: 10px;
		cursor: pointer;
		font-size: 14px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	.carrito-checkbox input {
		position: absolute;
		opacity: 0;
		cursor: pointer;
	}

	.checkmark {
		cursor: pointer;
		position: absolute;
		top: 0;
		left: 0;
		padding: 3px;
		margin-top: 2px;
		height: 15px;
		width: 15px;
		border: solid 1px #74c68366;;
		background-color: #fff;
	}

	.carrito-checkbox:hover input ~ .checkmark {
		border-color: #74c68366;
	}

	.carrito-checkbox input:checked ~ .checkmark {
		background-color: #74c683;
	}

	;

	.checkmark:after {
		content: "";
		position: absolute;
		display: none;
	}

	/* Show the checkmark when checked */
	.carrito-checkbox input:checked ~ .checkmark:after {
		display: block;
		color: white;
	}

	/* Style the checkmark/indicator */
	.carrito-checkbox .checkmark:after {
		left: 9px;
		top: 5px;
		width: 5px;
		height: 10px;
		border: solid white;
		border-width: 0 3px 3px 0;
		-webkit-transform: rotate(45deg);
		-ms-transform: rotate(45deg);
		transform: rotate(45deg);
	}

	.custom-card {
		background: #fff;
		padding-top: 8px;
		padding-bottom: 1px;
		margin-bottom: 2px;
		-webkit-box-shadow: 0px 5px 5px 0px rgba(176, 177, 193, 0.1);
		box-shadow: 0px 5px 5px 0px rgba(176, 177, 193, 0.1)
	}

	.margin-0 {
		margin: 0;
	}

	.category-text, .result-count {
		font-size: 14px;
		color: #111;
		font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
	}

	.category-text {
		font-size: 16px;
		font-weight: 600;
	}

	.result-count {
		font-size: 11px;
		color: #000000;
	}

	.filter-divider {
		color: #1fbb31;
		margin-left: 40px;
		margin-right: 40px;
	}

	.filter-text, .sort-text {
		color: #51a151;
		font-size: 13px;
		font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
	}

	.mobile-product {
		padding: 15px 0;
		background: #fff;
		-webkit-transition: 0.3s;
		-moz-transition: 0.3s;
		-o-transition: 0.3s;
		-ms-transition: 0.3s;
		transition: 0.3s;
		position: relative;
		min-height: 160px !important;
		max-height: 160px !important;
		margin-bottom: -5px;
	}
</style>
</head>
<body>
<div class="global-wrapper clearfix" id="global-wrapper">
	<?php $this->load->view('landing/resources/mobile/mobile-menu'); ?>

	<div id="ont_filter" class="filterbar">
		<div class="w-bg top_menu">
			<a href="javascript:void(0)" class="update_fil filter_btn_submit" style="float: right">Update
				Filter</a>
			<p><span class="filter_close_btn"><i class="fa fa-arrow-left" aria-hidden="true"></i></span> &nbsp;Filter
			</p>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading filter-head filter-first">Price</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-4" style="padding-right: 3px">
						<input type="text" class="form-control price_min" placeholder="&#8358; Min">
					</div>
					<div class="col-xs-4" style="padding-left:  3px !important; padding-right: 3px;">
						<input type="text" class="form-control price_max" placeholder="&#8358; Max">
					</div>
					<div class="col-xs-4">
						<input type="submit" class="price-submit" value="Go">
					</div>
				</div>
			</div>
		</div>

		<!--Brands-->
		<?php if (!empty($brands)): ?>
			<div class="panel panel-default">
				<div
					class="panel-heading filter-head">Brand
					<span style="color: #4c4c4c !important; float: right"><i class="fa fa-minus close-panel"
																			 aria-hidden="true"
																			 data-target="brand_static_vl"></i></span>
				</div>
				<div class="panel-body" id="brand_static_vl">
					<?php foreach ($brands as $brand) : ?>
						<div class="carrito-checkbox">
							<label class="list-label">
								<input class="filter" type="checkbox" name="filterset"
									   data-type="brand_name"
									   data-value="<?= trim($brand->brand_name); ?>"/><?= ucfirst($brand->brand_name); ?>
								<span class="checkmark"></span>
							</label>
						</div>
						<hr class="panel-line"/>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<!--Main Colour-->
		<?php if (!empty($colours)) : ?>
			<div class="panel panel-default">
				<div
					class="panel-heading filter-head">Main Colour
					<span style="color: #4c4c4c !important; float: right"><i class="fa fa-minus close-panel"
																			 aria-hidden="true"
																			 data-target="color_static_vl"></i></span>
				</div>
				<div class="panel-body" id="color_static_vl">
					<?php foreach ($colours as $colour) : ?>
						<div class="carrito-checkbox">
							<label class="list-label">
								<input class="filter" type="checkbox" name="filterset"
									   data-type="main_colour"
									   data-value="<?= trim($colour->colour_name); ?>"/><?= ucfirst($colour->colour_name); ?>
								<span class="checkmark"></span>
							</label>
						</div>
						<hr class="panel-line"/>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<!--Features-->
		<?php if ($features) : ?>
			<?php $x = 1;
			foreach ($features as $feature => $feature_value) : ?>
				<div class="panel panel-default">
					<div
						class="panel-heading filter-head"><?= preg_replace("/[^A-Za-z 0-9]/", ' ', $feature); ?>
						<span style="color: #4c4c4c !important; float: right"><i class="fa fa-minus close-panel"
																				 aria-hidden="true"
																				 data-target="<?= $feature ?>_vl"></i></span>
					</div>
					<div class="panel-body" id="<?= $feature ?>_vl">
						<?php foreach ($feature_value as $key => $value) : ?>
							<div class="carrito-checkbox">
								<label class="list-label">
									<input class="filter" type="checkbox" name="filterset"
										   data-type="<?= trim($feature); ?>"
										   data-value="<?= trim(preg_replace("/[^A-Za-z0-9-]/", '_', $value)) ?>"/><?= $value; ?>
									<span class="checkmark"></span>
								</label>
							</div>
							<hr class="panel-line"/>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>


	</div>

	<div class="">
		<div class="row">
			<div id="category_body">
				<!--Category Title Section-->
				<div class="custom-card">
					<div class="container">
						<p class="margin-0 text-center"><span
								class="category-text "><?= ucwords($category_detail->name); ?> Category</span>
						</p>
						<p class="text-center result-count"><?= number_format(count($products)) . ' products found'; ?> </p>
					</div>
				</div>

				<div class="custom-card">
					<div class="container">
						<p style="text-align: center"><span class="filter-btn filter-text"><i class="fa fa-filter"
																							  aria-hidden="true"></i> Filter</span>
							<span class="filter-divider">|</span> <span class="sort-text"><i class="fa fa-sort"
																							 aria-hidden="true"></i> Sort</span>
						</p>
					</div>
				</div>
				<div class="col-xs-12 ">
					<div class="row row-sm-gap" data-gutter="10">
						<?php foreach ($products as $product) : ?>
							<div class="col-md-3">
								<div class="mobile-product product-sm-left ">
									<ul class="product-labels"></ul>
									<div class="product-img-wrap">
										<img class="product-img lazy"
											 data-src="<?= PRODUCTS_IMAGE_PATH .$product->image_name;  ?>"
                                             src="<?= base_url('assets/landing/img/load.gif'); ?>"
											 alt="<?= $product->product_name; ?>"
											 title="<?= $product->product_name; ?>"/>
									</div>
									<a class="product-link"
									   href="<?= base_url(urlify($product->product_name, $product->id)); ?>"></a>
									<div class="product-caption">
										<ul class="product-caption-rating">
											<?php
											$rating_counts = $this->product->get_rating_counts($product->id);
											if ($rating_counts) {
												$overall_rating = product_overall_rating($rating_counts);
												$rating_rounded = round($overall_rating);
												for ($i = 1; $i <= $rating_rounded; $i++) {
													?>
													<li class="rated"><i class="fa fa-star"></i>
													</li>
													<?php
												}
												if ($rating_rounded < 5) {
													for ($i = 0; $i < (5 - $rating_rounded); $i++) { ?>
														<li><i class="fa fa-star"></i></li>
														<?php
													}
												}
											} else {
												?>
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
												<li><i class="fa fa-star"></i></li>
												<?php
											}
											?>
										</ul>
										<h5 class="product-caption-title"><?= word_limiter(ucwords($product->product_name), 14, '...'); ?></h5>
										<h4 class="product-caption-title">
											<strong>Seller: </strong><?= ucfirst($product->first_name); ?></h4>
										<div class="product-caption-price">
											<?php if (!empty($product->discount_price)) : ?> <span
												class="product-caption-price-old"><?= ngn($product->sale_price); ?></span>
												<span
													class="product-caption-price-new"><?= ngn($product->discount_price); ?></span>
											<?php else : ?>

												<span
													class="product-caption-price-new"><?= ngn($product->sale_price); ?></span>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div><!-- col-md-9 -->
			</div>
		</div> <!-- // row -->
	</div>


	<script src="<?= base_url('assets/landing/js/jquery.js'); ?>"></script>
	<script src="<?= base_url('assets/landing/js/bootstrap.js'); ?>"></script>
	<script src="<?= base_url('assets/landing/js/ionrangeslider.js'); ?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
		let current_url = "<?= current_url()?>";
	</script>
	<script src="<?= base_url('assets/landing/js/search.js'); ?>"></script>
	<script>
        $(function() {
            $('.lazy').Lazy();
        });
		$(document).ready(function () {
			let _category_body = $('#category_body');

			function doReplaceState(url) {
				let state = {current_url: url},
					title = "Carrito MarketPlace";
				history.replaceState(state, title, url);
			}

			function load_page(url) {

				$(_category_body).load(`${url} #category_body`, function (response, status, xhr) {
					if (status === "error") {
						let msg = "Sorry but there was an error: ";
						alert(msg + xhr.status + " " + xhr.statusText);
					}

					$('.close-panel').on('click', function (e) {
						e.preventDefault();
						let target = $(this).data('target');
						$(this).toggleClass("fa-minus fa-plus");
						$(`#${target}`).toggle()
					});

					$('.filter_close_btn').on('click', function () {
						filterBarClose();
					});
					// filterBarOpen();
					$('.filter-btn').on('click', function () {
						filterBarOpen();
					});

					// Side bar
					function filterBarOpen() {
						// $('#ont_filter').css({'width': '100%'});
						$('#ont_filter').show();
					}

					function filterBarClose(callback = '', value = '') {
						// $('#ont_filter').css({'width': 0});
						$('#ont_filter').fadeOut(function () {
							if (callback) {
								callback(value);
							}
						});

					}

					doReplaceState(url.toLowerCase());

					$('#processing').hide();
					$(_category_body).show();
				});
			}

			let url = '';
			let filter_string = '';
			$('.filter').change(function () {
				if ($('input[name=filterset]').is(':checked')) {
					let filter_list = {};
					url = '';
					filter_string = '';
					$(_category_body).hide();
					$('#processing').show();
					let items = $('input[name=filterset]:checked');

					items.each(function () {
						let value = $(this).data('value');
						let key = $(this).data('type');

						if (filter_list[key]) {
							if (jQuery.inArray(value, filter_list[key]) !== -1) {
							} else {
								filter_list[key].push(value)
							}
						} else {
							filter_list[key] = [value];
						}
						url = '';
						jQuery.each(filter_list, function (obj) {
							filter_string = '';
							jQuery.each(filter_list[obj], function (id, values) {
								if (filter_string === '') {
									filter_string += values;
								} else {
									filter_string += ',' + values;
								}
							});
							if (url === '') {
								url += `?${obj}=${filter_string}`
							} else {
								url += `&${obj}=${filter_string}`
							}
						});
						// load_page(url);
						console.log(url);

					});
				} else {
					load_page(current_url);
				}
			});
			$('.filter_btn_submit').on('click', function () {
				filterBarClose(load_page, url);
				// console.log(url + 'Current index');
				// load_page(url);
			});
		});


		$('.close-panel').on('click', function (e) {
			e.preventDefault();
			let target = $(this).data('target');
			$(this).toggleClass("fa-minus fa-plus");
			$(`#${target}`).toggle()
		});

		$('.filter_close_btn').on('click', function () {
			filterBarClose();
		});
		// filterBarOpen();
		$('.filter-btn').on('click', function () {
			filterBarOpen();
		});

		// Side bar
		function filterBarOpen() {
			// $('#ont_filter').css({'width': '100%'});
			$('#ont_filter').show();
		}

		function filterBarClose(callback = '', value = '') {
			// $('#ont_filter').css({'width': 0});
			$('#ont_filter').fadeOut(function () {
				if (callback) {
					callback(value);
				}
			});

		}
	</script>

	<script src="<?= base_url('assets/landing/js/mobile.js'); ?>"></script>
	<?php $this->load->view('landing/resources/mobile/mobile-footer'); ?>
</div>


</body>
</html>
