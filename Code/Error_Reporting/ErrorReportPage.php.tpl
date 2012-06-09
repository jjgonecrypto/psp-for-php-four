<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns:psp="http://www.justinjmoses.com.au/psp"  >
<head>

    <style type="text/css">
		body
		{
			font-family: verdana; 
			font-size: 0.8em;
		}
		.PSPErrorPanel
		{
			
		}
		
		.PSPErrorPanel .PSPErrorTable
		{
			background-color:red;
			color:white;
			border: 1px solid gray;
			border-collapse:collapse;
			
		}
		
		.PSPErrorPanel .PSPErrorTable th
		{
			text-align: right;
			padding: 3px;
			
			border: 1px solid #cccccc;
		}
		
		.PSPErrorPanel .PSPErrorTable td
		{
			padding: 3px;
			border: 1px solid #cccccc;
		}
    </style>
</head>
<body>
	
	<div class='PSPErrorPanel'>
		
		<h3>Error Found!</h3>
	
		<table class="PSPErrorTable">
			<tr><th>Error Class:</th><td><psp:literal runat="server" id="errorClass" /></td></tr>
			<tr><th>Message:</th><td><psp:literal runat="server" id="errorMessage" /></td></tr>
			<tr><th>File:</th><td><psp:literal runat="server" id="errorFile" /></td></tr>
			<tr><th>Line:</th><td><psp:literal runat="server" id="errorLine" /></td></tr>
		</table>
	
		<h4>Stack Trace</h4>
		
		<psp:grid runat="server" id="stackGrid" heading="Stack Trace" selector="none" enablepaging="false" />
		
	</div>
		

</body>
</html>