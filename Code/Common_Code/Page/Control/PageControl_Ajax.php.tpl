<div xmlns:psp="http://web.justinjmoses.com.au/psp">
	<script type="text/javascript" language="javascript">
		jsi.ajax.load();
	</script>
	
	<script type="text/javascript" language="javascript">
	
		//will be rendered for each control instance
		var tmp_ajax = jsi.psp.addControlInstance('[= this.Type]',new jsi.psp.Ajax('[= this.ID]','[= this.GetClientID]'));
		
		//for this instance, need to append input objects
		<psp:repeater runat="server" id="rptInput">
			<template>
				tmp_ajax.addInput('<item repeater="rptInput" datafield="label" />', '<item repeater="rptInput" datafield="objectID" />');
			</template>
		</psp:repeater>
		
		//for this instance, need to append output objects
		<psp:repeater runat="server" id="rptOutput">
			<template>
				tmp_ajax.addOutput('<item repeater="rptOutput" datafield="label" />', '<item repeater="rptOutput" datafield="objectID" />');
			</template>
		</psp:repeater>
		
		//for this instance, need to append startup functions
		<psp:repeater runat="server" id="rptStartup">
			<template>
				tmp_ajax.addStartup(<item repeater="rptStartup" datafield="objectID" />);
			</template>
		</psp:repeater>
		
		//for this instance, need to append complete functions
		<psp:repeater runat="server" id="rptComplete">
			<template>
				tmp_ajax.addComplete(<item repeater="rptComplete" datafield="objectID" />);
			</template>
		</psp:repeater>
		
		<psp:literal runat="server" id="litOutputFunction" />
	</script>
</div>