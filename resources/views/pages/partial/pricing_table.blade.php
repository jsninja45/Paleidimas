<table class="pricing-table">
    <tr>
        <th>Turnaround Time</th>
        <th>One-On-One Interview</th>
        <th>Group Discussions</th>
    </tr>
    <tr>
        <td>
            Standard Service
            <span class="notice">Up to 14 days!</span>
        </td>
        <td>$ {{ round($tat_array[14]->client_price_per_minute, 2) }}/min</td>
        <td>$ {{ round($tat_array[14]->client_price_per_minute, 2) + $speaker_price }}/min</td>
    </tr>
    <tr>
        <td>7-Days Service</td>
        <td>$ {{ round($tat_array[7]->client_price_per_minute, 2) }}/min</td>
        <td>$ {{ round($tat_array[7]->client_price_per_minute, 2) + $speaker_price }}/min</td>
    </tr>
    <tr>
        <td>3-Days Service</td>
        <td>$ {{ round($tat_array[3]->client_price_per_minute, 2) }}/min</td>
        <td>$ {{ round($tat_array[3]->client_price_per_minute, 2) + $speaker_price }}/min</td>
    </tr>
    <tr>
        <td>1-Day Service</td>
        <td>$ {{ round($tat_array[1]->client_price_per_minute, 2) }}/min</td>
        <td>$ {{ round($tat_array[1]->client_price_per_minute, 2) + $speaker_price }}/min</td>
    </tr>
</table>