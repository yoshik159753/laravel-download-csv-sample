<!doctype html>
<html lang="{{ app()->getLocale() }}" class="h-100">

<head>
  <title>Laravel Download CSV SamplePJ</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    #loading {
      /* ロード中の全画面操作抑止用クラス */
      display: table;
      width: 100%;
      height: 100%;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #fff;
      opacity: 0.8;
    }

    #loading .loadingMsg {
      /* ロード中の全画面操作抑止用クラス */
      display: table-cell;
      text-align: center;
      vertical-align: middle;
      padding-top: 140px;
      background: url("/images/loading.gif") center center no-repeat;
    }
  </style>
</head>

<body class="d-flex flex-column h-100">
  <header class="pb-5">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">Laravel Download CSV SamplePJ</a>
    </nav>
  </header>

  <!-- Begin page content -->
  <main role="main" class="flex-shrink-0 pt-5 container">
    <div class="row">
      <div class="offset-2 col-8">

        <div class="row buttons">
          <div class="col">
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase1') }}" role="button">DL CSV Case1</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase2') }}" role="button">DL CSV Case2</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase3') }}" role="button">DL CSV Case3</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase4') }}" role="button">DL CSV Case4</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase5') }}" role="button">DL CSV Case5</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase6') }}" role="button">DL CSV Case6</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase7') }}" role="button">DL CSV Case7</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase8') }}" role="button">DL CSV Case8</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase9') }}" role="button">DL CSV Case9</a>
            <a class="btn btn-primary downloadCsv" href="{{ route('downloadCsvCase9v2') }}" role="button">DL CSV Case9v2</a>
          </div>
        </div>

        <div class="row table pt-5">
          <div class="col">
            <ul class="nav nav-tabs" id="tablesTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="children-table-tab" data-toggle="tab" href="#children-table" role="tab"
                  aria-controls="children-table" aria-selected="true">子</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="parents-table-tab" data-toggle="tab" href="#parents-table" role="tab"
                  aria-controls="parents-table" aria-selected="false">親</a>
              </li>
            </ul>
            <div class="tab-content" id="tablesTabContent">
              <div class="tab-pane fade show active" id="children-table" role="tabpanel"
                aria-labelledby="children-table-tab">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">氏名</th>
                      <th scope="col">氏名ｶﾅ</th>
                      <th scope="col">性別</th>
                      <th scope="col">誕生日</th>
                      <th scope="col">所属クラス</th>
                      <th scope="col">コメント</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($children as $child)
                    <tr>
                      <th scope="row">{{ $child->id }}</th>
                      <td>{{ $child->name }}</td>
                      <td>{{ $child->kana }}</td>
                      <td>{{ $child->sex }}</td>
                      <td>{{ $child->birthday }}</td>
                      <td>{{ $child->classes->implode('class.name', ', ') }}</td>
                      <td>{{ $child->comment }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                *TODO: ページネート
              </div>
              <div class="tab-pane fade" id="parents-table" role="tabpanel" aria-labelledby="parents-table-tab">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">氏名</th>
                      <th scope="col">氏名ｶﾅ</th>
                      <th scope="col">性別</th>
                      <th scope="col">郵便番号</th>
                      <th scope="col">住所</th>
                      <th scope="col">電話番号</th>
                      <th scope="col">メールアドレス</th>
                      <th scope="col">コメント</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($parents as $parent)
                    <tr>
                      <th scope="row">{{ $parent->id }}</th>
                      <td>{{ $parent->name }}</td>
                      <td>{{ $parent->kana }}</td>
                      <td>{{ $parent->sex }}</td>
                      <td>{{ $parent->zip }}</td>
                      <td>{{ $parent->address }}</td>
                      <td>{{ $parent->tel }}</td>
                      <td>{{ $parent->email }}</td>
                      <td>{{ $parent->comment }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                *TODO: ページネート
              </div>
            </div>
          </div>
        </div>

      </div><!-- main col -->
    </div><!-- main row -->
  </main>

  <footer class="footer mt-auto py-3 bg-light">
    <div class="container">
      <span class="text-muted">&nbsp;</span>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
  </script>
  <script>
    window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery-slim.min.js"><\/script>')
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
  <script type="text/javascript">
    $('a.downloadCsv').on('click', function () {
      console.time('timerKeyDownloadCsv');
      // 開始前に監視対象の cookie をクリア
      Cookies.remove('watchKeyDownloadCsv')
      // ローディング表示
      $("body").append("<div id='loading'><div class='loadingMsg'></div></div>");
      // 100ms による setInterval
      var intervalId = setInterval(function () {
        // 期待する cookie が存在するか watch
        if (Cookies.get('watchKeyDownloadCsv')) {
          console.timeEnd('timerKeyDownloadCsv');
          // cookie が存在する場合は処理完了としてローディング画面を止める
          $("#loading").remove();
          // intervalの処理を止める
          clearInterval(intervalId);
          // 受け取った cookie をクリア
          Cookies.remove('watchKeyDownloadCsv')
        }
      }, 100);
    });
  </script>
</body>

</html>
