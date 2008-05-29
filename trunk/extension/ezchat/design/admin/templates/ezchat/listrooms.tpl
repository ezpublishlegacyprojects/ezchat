<div class="context-block">
	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">{'Found chatrooms'|i18n('design/admin/ezchat')}</h1>
		<div class="header-mainline"></div>
	</div></div></div></div></div></div>

	<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

		<table class="list" cellspacing="0">
			<tr>
				<th align="left">Name</th>
				<th align="left">Type</th>
				<th align="left">Status</th>
				<th align="left">#Lock</th>
			</tr>

			<tr class="{$sequence}">
				<td>mettre ici les rooms de base</td>
				<td>Static (id=)</td>
				<td>Opened/closed/heures d'ouvertures</td>
				<td>mettre ici une checkbox, et  un bouton tout en bas</td>
			</tr>

			{def $chatrooms=fetch('ezchat', 'chatroomlist')}
			{def $daysOfWeek=array(	'Sunday'|i18n( 'ezchat/datatypes/chatroom'),
									'Monday'|i18n( 'ezchat/datatypes/chatroom'),
									'Tuesday'|i18n( 'ezchat/datatypes/chatroom'),
									'Wednesday'|i18n( 'ezchat/datatypes/chatroom'),
									'Thursday'|i18n( 'ezchat/datatypes/chatroom'),
									'Friday'|i18n( 'ezchat/datatypes/chatroom'),
									'Saturday'|i18n( 'ezchat/datatypes/chatroom') )}
			{foreach $chatrooms as $room sequence array('bgdark','bglight') as $sequence}
				{def $roomObject=fetch('content','object',hash('object_id',$room.object_id))}
				<tr class="{$sequence}">
					<td><a href={concat('/ezchat/room/(channel)/',$room.chatroom.name)|ezurl}>{$room.chatroom.name}</a></td>
					<td>#Object (id={$room.object_id}): <a href={$roomObject.main_node.url_alias|ezurl} title="#Go to main node location">{$roomObject.main_node.name|wash}</a> #bouton de modif ici#</td>
					<td>
						<b>Actually {if $room.chatroom|chat_opened}##Opened{else}##Closed{/if}</b> -
						{'Opened from %1h00 to %2h00.'|i18n( 'ezchat/datatypes/chatroom', '', array($room.chatroom.start_value, $room.chatroom.stop_value) )}<br/>
						({foreach $room.chatroom.option_list.3.value|explode('-') as $item}
							{$daysOfWeek.$item}
							{delimiter}, {/delimiter}
						{/foreach})
					</td>
					<td>##checkbox</td>
				</tr>
			{/foreach}
			{undef $chatrooms $daysOfWeek}
		</table>

	</div></div></div></div></div></div>

</div>