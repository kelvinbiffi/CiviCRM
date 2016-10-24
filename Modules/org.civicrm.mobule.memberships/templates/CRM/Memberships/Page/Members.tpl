

<h2>Members list</h2>
<form action="index.php?q=civicrm/members&reset=1" method="post">
  { if $strMessage }
    <span class="error"><b>{ $strMessage }</b></span><br>
  {/if}
  <label for="strStartDate">
    Start Date:
    <input type="text" name="strStartDate" id="strStartDate" placeholder="MM/DD/YYYY" value="{$strStartDate}">
  </label><br>
  <label for="strEndDate">
    End Date:
    <input type="text" name="strEndDate" id="strEndDate" placeholder="MM/DD/YYYY" value="{$strEndDate}">
  </label><br>
  <input type="submit" name="action" value="Search">
</form>
<table>
 <tr>
  <th>Id</th>
  <th>Name</th>
  <th>Source</th>
  <th>Type</th>
  <th>Start date</th>
  <th>End date</th>
 </tr>
  {foreach from=$arrMemberships  item=member}
    <tr>
     <td>{ $member.id }</td>
     <td>{ $member.name }</td>
     <td>{ $member.source }</td>
     <td>{ $member.type }</td>
     <td>{ $member.start_date }</td>
     <td>{ if $member.end_date }{ $member.end_date }{else}{'--'}{/if}</td>
    </tr>
  {/foreach}
</table>
<h3>Report generated at {$currentTime}</h3>
