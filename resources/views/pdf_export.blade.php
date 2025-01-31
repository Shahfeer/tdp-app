<!DOCTYPE html>
<html>
<head>
    <title>Exported Data</title>
    <style>
        /* Define styles for your PDF content here */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Exported Data</h1>
    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Campaign Name</th>
								<th>Receiver Mobile No</th>
                <th>Sender Mobile No</th>
                <th>Call Status</th>
								<th>Retry Count</th>
								<th>Call Duration (In Secs)</th>
								<th>Context</th>
								<th>First Call Time</th>
								<th>Last Call Time</th>
								<th>Call End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['campaign_name'] }}</td>
					<td>{{ $row['dst'] }}</td>
					<td>{{ $row['src'] }}</td>
					<td>{{ $row['disposition'] }}</td>
					<td>{{ $row['retry_count'] }}</td>
					<td>{{ $row['billsec'] }}</td>
					<td>{{ $row['context'] }}</td>
					<td>{{ $row['calldate'] }}</td>
					<td>{{ $row['last_call_time'] }}</td>
					<td>{{ $row['hangupdate'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
