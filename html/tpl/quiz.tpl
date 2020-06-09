

<div class="modal_container step_<?=$step?> active" data-step="<?=$step?>" <?=isset($id_user)?'data-id_user="'.$id_user.'"':null;?> <?=isset($target_id_order)?'data-target_id_order="'.$target_id_order.'"':null;?>>
	<?switch($step){
		case 1:
			if(isset($saved_addresses)){?>
				<div class="quiz_header">
					<h6>Здравствуйте, <?=$customer['first_name']?> <?=$customer['middle_name']?>! Меня зовут <?=$contragent?> и я сопровождаю Ваш заказ.</h6>
					<span>Вы уже указывали свой адрес. Хотите использовать его или добавить новый?</span>
				</div>
				<div class="quiz_content">
					<form action="<?=$_SERVER['REQUEST_URI']?>" class="mdl-grid">
				<div class="demo-list-action mdl-list">
					<?foreach($saved_addresses as $address){?>
							<div class="mdl-list__item">
									<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="<?=$address['id']?>">
										<input type="radio" id="<?=$address['id']?>" class="mdl-radio__button" name="address">
										<div class="mdl-radio__label"><?=$address['shipping_company']?> <?=$address['delivery']?></div>
										<div class="mdl-radio__label"><?=$address['address']?></div>
										<div class="mdl-radio__label"><?=$address['delivery_department']?$address['delivery_department']:$address['housenumber'].' '.$address['apartmentnumber']?></div>
									</label>
							</div>
						<?}?>
				</div>
						<div class="mdl-cell mdl-cell--12-col">
							<div class="address_preview"></div>
						</div>
					</form>
				</div>
			<?}else{
				if($customer['first_name'].$customer['middle_name'].$customer['last_name'] != ''){
					$client_name = $customer['first_name'].' '.$customer['middle_name'].' '.$customer['last_name'];
				}else{
					$client_name = $_SESSION['member']['name'];
				}?>
				<div class="quiz_header">
					<h6>Здравствуйте! Меня зовут <?=$contragent?> и я сопровождаю Ваш заказ.</h6>
					<span>Сейчас я вижу Вас как <?=$client_name?>, скажите, как Вас зовут?</span>
				</div>
				<div class="quiz_content">
					<form action="<?=$_SERVER['REQUEST_URI']?>" class="mdl-grid">
						<div class="mdl-cell mdl-cell--12-col">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="last_name">
								<input class="mdl-textfield__input" type="text" name="last_name" value="<?=$customer['last_name']?>">
								<label class="mdl-textfield__label" for="last_name">Фамилия</label>
								<span class="mdl-textfield__error">Введите фамилию</span>
							</div>
						</div>
						<div class="mdl-cell mdl-cell--12-col">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="first_name">
								<input class="mdl-textfield__input" type="text" name="first_name" value="<?=$customer['first_name']?>">
								<!-- value="Александр"> -->
								<label class="mdl-textfield__label" for="first_name">Имя</label>
								<span class="mdl-textfield__error">Введите имя</span>
							</div>
						</div>
						<div class="mdl-cell mdl-cell--12-col">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="middle_name">
								<input class="mdl-textfield__input" type="text" name="middle_name" value="<?=$customer['middle_name']?>">
								<label class="mdl-textfield__label" for="middle_name">Отчество</label>
								<span class="mdl-textfield__error">Введите отчество</span>
							</div>
						</div>
					</form>
				</div>
			<?}
			break;
		case 2:?>
			<div class="quiz_header">
				<h6><span class="client"><?=$customer['first_name']?> <?=$customer['middle_name']?></span>, мы доставляем в <?=$cities_count?> городов Украины, <?=isset($saved_addresses)?'куда необходимо доставить заказ':'а откуда Вы'?>?</h6>
			</div>
			<div class="quiz_content">
				<form action="<?=$_SERVER['REQUEST_URI']?>" class="mdl-grid">
				  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    				<input class="mdl-textfield__input" type="number" pattern="-?[0-9]*(\.[0-9]+)?" id="postcode" onmouseout="postcodeSelect($(this));">
    				<label class="mdl-textfield__label" for="sample4">Почтовый индекс если знаете</label>
   					<span class="mdl-textfield__error">Input is not a number!</span>
  				</div>
					<div class="mdl-cell mdl-cell--12-col" id="postcodeSelect">
						<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label region">
							<select id="region" name="region" class="mdl-selectfield__select" required onChange="regionSelect($(this));">
								<option disabled selected>Выберите область</option>
								<?foreach($regions_list as $region){?>
									<option value="<?=$region->REGION_ID?>"><?=$region->REGION_UA?></option>
								<?}?>
							</select>
							<label class="mdl-selectfield__label" for="region">Область</label>
						</div>
						<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label district">
							<select id="district" name="district" class="mdl-selectfield__select" required disabled onChange="districtSelect($(this));">
							</select>
							<label class="mdl-selectfield__label" for="city">Район</label>
						</div>
						<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label city">
							<select id="city" name="city" class="mdl-selectfield__select" required disabled onChange="citySelect($(this));">
							</select>
							<label class="mdl-selectfield__label" for="city">Город (село)</label>
						</div>
					</div>
				</form>
			</div>
			<?break;
		case 3:?>
			<div class="quiz_header">
				<h6><span class="client"><?=$customer['first_name']?> <?=$customer['middle_name']?></span>, доставка в этот адрес <span class="city"></span> возможна! Выберите службу доставки и удобный для Вас способ.</h6>
			</div>
			<div class="quiz_content">
				<form action="<?=$_SERVER['REQUEST_URI']?>" class="mdl-grid">
					<div class="mdl-cell mdl-cell--12-col">	
						<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label delivery_service">
							<select id="id_delivery_service" name="id_delivery_service" class="mdl-selectfield__select" required onChange="deliveryServiceSelect($(this))">
								<option disabled selected>Выберите службу доставки</option>
								<?foreach($shipping_companies as $company){?>
									<option value="<?=$company['id']?>"><?=$company['title']?></option>
								<?}?>
							</select>
							<label class="mdl-selectfield__label" for="id_delivery_service">Служба доставки</label>
							<span class="mdl-textfield__error">Выберите службу доставки!</span>
						</div>'	
						<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label delivery is-disabled">
							<select id="id_delivery" name="id_delivery" class="mdl-selectfield__select" required disabled onChange="deliverySelect($(this))">

							</select>
							<label class="mdl-selectfield__label" for="id_delivery">Способ доставки</label>
							<span class="mdl-textfield__error">Выберите способ доставки!</span>
						</div>
					</div>
					<!--Появляется после выбора Способа доставки-->
					<div class="deliv"></div>					
				</form>
			</div>
			<?break;
		/*case 4:?>
			<div class="quiz_header">
				<h6><?=$customer['middle_name']?> <?=$customer['last_name']?>, у меня есть необходимые данные для отправки заказа.</h6>
				<span>Вы готовы внести предоплату?</span>
			</div>
			<div class="quiz_content">
				<div class="label_wrap">
					<label class="mdl-radio mdl-js-radio" for="option-6">
						<input type="radio" id="option-6" class="mdl-radio__button" name="options" value="6" checked>
						<span class="mdl-radio__label">Нет, мне необходима телефонная консультация.</span>
					</label>
					<label class="mdl-radio mdl-js-radio" for="option-7">
						<input type="radio" id="option-7" class="mdl-radio__button" name="options" value="7">
						<span class="mdl-radio__label">Да, предоставьте реквизиты!</span>
					</label>
				</div>
				<div class="company_details">
					<h4>Реквизиты компании</h4>
				</div>
			</div>
			<?break;*/
		case 4:?>
			<div class="quiz_header">
				<h6>Спасибо за Ваш заказ!</h6>
				<span>Вы хотите оплатить!</span>

        <form method="POST" action="https://api.privatbank.ua/p24api/ishop">
                <input type="hidden" name="amt" value="<?=$amaunt['sum_opt'];?>"/>
                <input type="hidden" name="ccy" value="UAH"/>
                <input type="hidden" name="merchant" value="150354"/>
                <input type="hidden" name="order" value="<?=$amaunt['id_order'];?>"/>
                <input type="hidden" name="details" value="<?=$customer['first_name']?> <?=$customer['middle_name']?>"/>
                <input type="hidden" name="ext_details" value="Хозтовары xt.ua"/>
                <input type="hidden" name="return_url" value="https://xt.ua/cabinet/orders?t=all"/>
                <input type="hidden" name="server_url" value="https://..."/>
                <input type="hidden" name="pay_way" value="privat24"/>
                <button type="submit" class="p24"><img src="p24.png" border="0" /></button>
        </form>



			</div>
			<div class="quiz_content"></div>
			<?break;
		default:
			# code...
			break;
	}?>
	<div class="row quiz_footer">
		<?if($step > 1 && $step < 4){?>
			<button class="mdl-button mdl-js-button to_step" data-step="<?=$step-1?>">Назад</button>
		<?}
		if($step < 5){?>
			<?if($step == 1 && isset($saved_addresses)){?>
				<button class="mdl-button mdl-js-button to_step create_new" data-step="<?=$step+1?>">Создать новый</button>
				<button class="mdl-button mdl-js-button to_step use_selected" data-step="<?=$step+1?>">Далее</button>
			<?}else{?>
				<button class="mdl-button mdl-js-button to_step" data-step="<?=$step+1?>"><?=$step==4?'Закрыть':'Далее';?></button>
			<?}?>
		<?}?>
		<div class="progress">
			<div class="line">
				<div class="line_active"></div>
			</div>
			<span class="go">Заполнено: </span>
		</div>
	</div>
</div>