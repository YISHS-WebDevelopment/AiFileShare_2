@extends('template.app')

@section('script')
    <script src="{{asset('/public/js/circles/sharePage.js')}}"></script>

@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('/public/css/folder/folder.css')}}">
@endsection
@section('contents')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="font-weight-bold">{{$detail}}({{$category === 'all' ? '전체' : $category.'학년'}})</h1>
            <a href="{{route('circles.detail',[$detail, $category])}}">
                <button class="btn btn-secondary">←</button>
            </a>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <div class="dropdown show">
                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ＋ 새로 만들기
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#share-modal">폴더</a>
                </div>
            </div>
        </div>
        <hr>
        <table class="table pl-4 pr-4 rounded shadow">
            <thead>
            <tr>
                <th><i id="file-icon" class="fa-solid fa-file"></i></th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            이름 ↑
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">텍스트 오름차순</a>
                            <a class="dropdown-item" href="#">텍스트 내림차순</a>
                        </div>
                    </div>
                </th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            수정한 날짜
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">오래된 순</a>
                            <a class="dropdown-item" href="#">최신 순</a>
                        </div>
                    </div>
                </th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            파일 크기
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">작은 숫자순</a>
                            <a class="dropdown-item" href="#">큰 숫자순</a>
                        </div>
                    </div>
                </th>
                <th>작성자</th>
                @if(auth()->user()->type === 'admin')
                    <th>관리</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Folder::where('folder_id',null)->where('circle_id',$detail)->where('grade_id', $category)->get() as $folder)
                <tr>
                    <td><img src="{{asset('/public/images/folder_icon.svg')}}" class="folder-icon" alt="folder_icon">
                    </td>
                    <td>
                        <a id="folder_{{$folder['id']}}"
                           href="{{route('folder.index',[$detail,$category,$folder['url']])}}">{{$folder->title}}</a>
                    </td>
                    <td>{{date('Y-m-d',strtotime($folder->created_at))}}</td>
                    <td>{{$folder->getFileSize($folder->size)}}</td>
                    <td>{{$folder->user->student_id}}{{$folder->user->username}}</td>

                    @if(auth()->user()->type === 'admin')
                        <td class="d-flex">
                            <form action="{{route('folder_manage.page')}}" method="get">
                                @csrf
                                <button class="btn btn-outline-primary" type="submit">관리</button>
                            </form>
                            <form action="{{route('folder.delete',$folder['id'])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-outline-danger ml-2" value="{{$folder['id']}}"
                                        onclick="return confirm('정말 삭제하시겠습니까? 하부 폴더와 파일들이 모두 삭제됩니다.');" id="folder_del">
                                    삭제
                                </button>
                            </form>
                        </td>
                    @endif

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="share-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h4 class="modal-title">폴더 만들기</h4>
                    <span class="font-weight-bold" style="cursor: pointer;font-size: 1.2rem"
                          data-dismiss="modal">X</span>
                </div>
                <form action="{{route('folder.create',[$detail,$category])}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" id="folder-input" placeholder="폴더 이름을 입력해주세요." name="title"
                               class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="submit">만들기</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
