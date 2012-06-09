<div class="PSPDataTableControl" gridcontrol="true"  xmlns:psp="http://web.justinjmoses.com.au/psp">
	<div class="PSPDataTableHeading">
		<psp:literal runat="server" id="litHeading" />
	</div>
	<psp:literal tag="div" class="PSPDataTableHelperText" runat="server" id="litHelperText" />
	
	<script type="text/javascript" language="javascript">
		jsi.table.load();
		jsi.ajax.load();
	</script>
	
	<script type="text/javascript" language="javascript">
	
		//will be rendered for each control instance
		
		var this_dtable = jsi.psp.addControlInstance("[= this.Type]",new jsi.psp.DataTable('[= this.ID]','[= this.GetClientID]','[= outputTable.GetClientID]','[= this.GetClientID]_divLoading'));
		
		this_dtable.setPageSize([= this.PageSize]);
		
		
		jsi.onload.addStartupFunction(
			function()
			{
				dtable = jsi.psp.getControlInstance("[= this.Type]","[= this.ID]");
				
				dtable.loadViaAjax();
			}
		);
	</script>
	
	
	<psp:inputgroup runat="server" id="selectorGroup" />
		
	<!-- Table content will go here -->		
	<div id="outputTable" runat="server"></div>
	
	<div class="PSPDataTableLoading" id="[= this.GetClientID]_divLoading">
		Loading, please wait... <div class="PSPDataTableLoadingInside"></div>
	</div>
	
	<div class="PSPDataTableButtons">
		<psp:placeholder runat="server" id="plhButtons" />
		<div class="PSPDataTableButtonsHelper">
			<psp:placeholder runat="server" id="plhButtonsHelper" />		
			<psp:literal runat="server" id="litBottomText" class="PSPDataTableBottomText" />
		</div>
		
	</div>
</div>