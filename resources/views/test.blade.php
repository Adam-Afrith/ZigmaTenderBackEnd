<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   
<style>
    /* .te{
        color:red

    } */
    /* .tab-border{
        border:5px solid red;
    } 
    Tets Edited Can Clear
    */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Define table header styles */
    .custom-table th {
        background-color: #ffffff;
        font-weight: bold;
        padding: 10px;
        border: 1px solid #ddd;
    }

    /* Define table row styles */
    .custom-table tr {
        background-color: #ffffff;
    }

    /* Define table cell styles */
    .custom-table td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    .logo-image{
        width:100px;
        height:100px;
    }
    .passage p{
        font-weight: bold;
    }
</style>
</head>
<body>

   <div>
    <div class="passage">
    <P>Dear Sir/Madam,<br>
    The following bids will expire based on the below information provided,</P>
    </div>
  

<table class="custom-table">
  <thead>
    <tr>
    <th scope="col">S.No</th>
      <th scope="col">Customer Name</th>
      <th scope="col">BidNo</th>
      <th scope="col">Country</th>
      <th scope="col">State</th>
      <th scope="col">District</th>
      <th scope="col">City</th>
      <th scope="col">NIT Date</th>
    <th scope="col">Submission Date</th>
    <th scope="col">Remaining Days</th>
    </tr>
  </thead>

  <tbody>
  @foreach($list as $index => $row)
        <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $row['customername'] }}</td>
        <td>{{ $row['BidNo'] }}</td>
        <td>{{ $row['country'] }}</td>
        <td>{{ $row['state'] }}</td>
        <td>{{ $row['district'] }}</td>
        <td>{{ $row['city'] }}</td>
        <td>{{ $row['nitdate'] }}</td>
        <td>{{ $row['submissiondate'] }}</td>
        <td>{{ $row['Remainingdays'] }}</td>
        </tr>
    @endforeach
  </tbody>

</table>
<div class="passage" >
<p style="margin-top: 18px;"> kindly do the needful.</p>
</div>
    
<div class="logo">
   
  <img src="{{asset('icons/logo1.png')}}" alt="Image" width="100" height="100">

   </div>
   <p>This is an auto generated mail.</p>
   </div>

  
</body>
</html>