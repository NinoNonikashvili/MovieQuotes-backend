@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{asset('logoForEmail.png')}}" class="logo" style="object-fit:cover; " alt="Laravel Logo">
<h3>{{__('email.app_title')}}</h3>
</a>
</td>
</tr>
