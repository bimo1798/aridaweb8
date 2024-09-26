<?php

function validateAccess()
{
	$ci = get_instance(); //ini untuk memanggil library CI di dalam fungsi is_logged_in
	if(!$ci->session->userdata('nik')) //dikasih tanda seru agar jika session yg login !(bukan)/ belum login ga bisa akses controler menu akses levelnya maka di tendang contoler index login(yaitu login)
	{
		redirect('login');
	}


}
