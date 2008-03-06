<div class="context-block">
	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">{$info.Name|wash}</h1>
		<div class="header-mainline"></div>

	</div></div></div></div></div></div>

	<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

		<table class="list" cellspacing="0">
			{foreach $info as $key => $value sequence array('bgdark','bglight') as $sequence}
				{if is_array($value)}
					<tr class="{$sequence}">
						<td width="33%">{$key|i18n('design/admin/ezchatinfo')}:</td>
						<td width="66%">
							<table class="list" cellspacing="0">
								{foreach $value as $key2 => $value2 sequence array('bglight','bgdark') as $sequence2}
									{if is_array($value2)}
										{* one more level needed ? *}
									{else}
										<tr class="{$sequence2}">
											<td width="33%">{$key2|i18n('design/admin/ezchatinfo')}:</td>
											<td width="66%">{$value2|i18n('design/admin/ezchatinfo')}</td>
										</tr>
									{/if}
								{/foreach}
							</table>
						</td>
					</tr>
				{else}
					<tr class="{$sequence}">
						<td width="33%">{$key|i18n('design/admin/ezchatinfo')}:</td>
						<td width="66%">{$value|i18n('design/admin/ezchatinfo')}</td>
					</tr>
				{/if}
			{/foreach}
		</table>

	</div></div></div></div></div></div>

A small try for GoogleMaps interface:
{set-block scope=root variable=cache_ttl}0{/set-block}
{include uri="design:ezchat/gmaps.tpl"}

</div>