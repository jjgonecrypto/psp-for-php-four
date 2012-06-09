<div class="PSPButton" buttonsrepeater="true" xmlns:psp="http://web.justinjmoses.com.au/psp">
	<a buttonlink="true" onclick="new function() { if ({attClientFnc} == true) { jsi.psp.doPostback('{attButton}'); } }; return false;" href="javascript: void(0);">
		<psp:attribute runat="server" id="attButton" attribute="onclick"  />
		<psp:attribute runat="server" id="attClientFnc" attribute="onclick"  />
		<div><psp:literal runat="server" id="buttonName" /></div>
	</a>
</div>