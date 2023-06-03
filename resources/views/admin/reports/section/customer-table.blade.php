@if(!empty($data))
    @php($row = 1)
    @php($sumCases = 0)
    @php($sumCasesTach = 0)
    @foreach($data as $customer => $value)
        @php($sumCases += $value['tasks_amount'])
        @php($sumCasesTach += $value['seperated_task_amount'])
        <tr>
            <td class="border text-center">{{ $row }}</td>
            <td class="border name">{{ $customer }}</td>
            <td class="border text-center">{{ $value['tasks_amount'] }}</td>
            <td class="border text-center">{{ $value['seperated_task_amount'] }}</td>
        </tr>
        @php($row += 1)
    @endforeach
    <tr>
        <td class="border"></td>
        <td class="border">Total</td>
        <td class="border text-center">{{ $sumCases }}</td>
        <td class="border text-center">{{ $sumCasesTach }}</td>
    </tr>
@endif