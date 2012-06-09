<div xmlns:psp="http://web.justinjmoses.com.au/psp" class="PSPInputTable">
	<script type="text/javascript" language="javascript">
		jsi.table.load();
	</script>
	
	<script type="text/javascript" language="javascript">
	
		jsi.psp.addControlInstance("[= this.Type]",new jsi.psp.InputTable('[= this.ID]','[= this.GetClientID]','[= this.Heading]','[= tableFormulas.GetClientID]','[= inData.GetClientID]'));
			
		jsi.onload.addStartupFunction(
			function()
			{
				itable = jsi.psp.getControlInstance("[= this.Type]","[= this.ID]");
				
				
				//add the cell templates
				itable.table.loadTemplates(jsi.dhtml.loadElement("[= cellTemplates.GetClientID]"));
				
				itable.table.startup();
				
				//insert predata
				<psp:repeater runat="server" id="rptPreloader">
					<template>
						itable.table.insertRow(new Array(<psp:literal runat="server" id="litInserter" />));
						itable.table.updateRow();
					</template>
				</psp:repeater>
				
				//add a new row
				itable.table.insertRow();
            
				itable.table.draw();
			}
		);
		
		
	</script>
	
	<div id="tableFormulas" runat="server"></div>

	<div style="display: none;" id="cellTemplates" runat="server"></div>
	
	<psp:input type="hidden" runat="server" id="inData" jsi_validate="false" jsi_label="[= this.Heading]" jsi_validationtype="text" />
	
	
</div>