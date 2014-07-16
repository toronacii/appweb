
<div id="rentasInfo">

		<?if ((@$log=='1')|| (isset($_GET['express_desde_alcaldia']))||(isset($_GET['eventual_desde_alcaldia']))){
			$data['log']=@$log;
			$this->load->view('express/express_view',$data);
		}else if (@$log=='2'){
			$data['log']=$log;
			$this->load->view('oficina_virtual/oficina_login_view',$data);
		}else if (!@$log){
		
		
		$this->load->view('oficina_virtual/oficina_login_view');
		
		}?>
<div class="clearE"></div>
</div>



