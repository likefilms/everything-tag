<div class="row list">
  <div class="panel panel-default">
    <div class="panel-body">
      <a href="#" class="welcome-logo"><img src="/img/logo.png" alt=""></a>
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Tags</th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $video)
          	 @include('videos::public._list-item')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
