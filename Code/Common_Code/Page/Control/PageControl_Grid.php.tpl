<div class="PSPGridControl" gridcontrol="true"  xmlns:psp="http://web.justinjmoses.com.au/psp">
	<div class="PSPGridHeading">
		<psp:literal runat="server" id="litHeading" />
	</div>
	<psp:literal tag="div" class="PSPGridHelperText" runat="server" id="litHelperText" />
	
	
			
	<table cellspacing="0" cellpadding="0" class="PSPGridTable">
		<thead>
		<tr class="PSPGridHeaders">
	
			<th runat="server" id="selectorHeading" class="PSPGridSelectorHeader">
				<input type="checkbox" onclick="selectAll();" runat="server" id="chkSelectall" />
			</th>
	
			<psp:repeater runat="server" id="rptColHeaders">
				<template>
					<th>
						<item repeater="rptColHeaders" datafield="Heading" />
					</th>
				</template>
			</psp:repeater>		
		</tr>
		</thead>
		
		<tbody>
			<psp:inputgroup runat="server" id="selectorGroup" />
			<psp:repeater runat="server" id="rptRows">
				<template>
				<tr class="PSPGridRow" onmouseover="_psp_grid.mouseoverRow(this);" onmouseout="_psp_grid.mouseoutRow(this);" onmousedown="_psp_grid.mousedownRow('[= ciSelector.GetClientID]');" >
					<td class="PSPGridRowSelector" runat="server" id="rowSelector">
						<psp:groupitem runat="server" id="ciSelector" group="selectorGroup" onclick="_psp_grid.checkitemClicked(this);" />
					</td>
					<psp:repeater runat="server" id="rptCols">
						<template>
						<td class="{itemClass}">
							<item id="itemClass" repeater="rptCols" datafield="CssClass" forparent="true" attribute="class" />
							<item repeater="rptRows">
								<datafield>
									<item repeater="rptCols" datafield="Datafield" />
								</datafield>
							</item>
						</td>	
						</template>
					</psp:repeater>
				</tr>
				</template>
			</psp:repeater>
		</tbody>
		
	</table>
	
	<div class="PSPGridButtons">
		<psp:placeholder runat="server" id="plhButtons" />
		<div class="PSPGridButtonsHelper">
			<psp:placeholder runat="server" id="plhButtonsHelper" />		
		</div>
		
	</div>
</div>