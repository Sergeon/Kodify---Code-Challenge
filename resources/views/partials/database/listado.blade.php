<div class=""  >

  {!! $pagination !!}  


    <table class="bordered highlighted"  >
        <thead>
            <tr>
                @if( in_array('detail' , $actions) )
                    <th> </th>
                @endif

                @foreach ($keys as $key )
                    <th>
                        {{$key}}
                    </th>
                @endforeach

                @if (in_array('delete' , $actions))
                    <th></th>
                @endif

                @if (in_array('add' , $actions))
                    <th></th>
                @endif

                @if (in_array('accept' , $actions))
                    <th></th>
                @endif

                @if (in_array('reject' , $actions))
                    <th></th>
                @endif
            </tr>

        </thead>

        <tbody>


            @foreach ($values as $value )
                <tr>
                    @if( in_array('detail' , $actions) )
                        <td>
                            <a href="{{ $util['detail_uri'] . $value['id'] }}">
                                <i class="material-icons detalle small circle">open_in_new</i>
                            </a>
                        </td>
                    @endif

                    @foreach ($keys as $key )
                        <td>
                            {{ $value[$key] }}
                        </td>
                    @endforeach

                    @if (in_array('delete' , $actions))
                        <td>
                            <a href="#" data-model="{{ $util['model_name'] }}"  data-id="{{ $value['id'] }}" class="delete-table-link" >
                                <i class="material-icons delete small circle">delete</i>
                            </a>
                        </td>
                    @endif

                    @if (in_array('add' , $actions))
                        <td>
                            <a href="#" data-model="{{ $util['model_name'] }}"  data-id="{{ $value['id'] }}" class="add-table-link" >
                                <i class="material-icons delete  small circle">thumb_up</i>
                            </a>
                        </td>
                    @endif


                    @if (in_array('accept' , $actions))
                        <td>
                            <a href="#" data-model="{{ $util['model_name'] }}"  data-id="{{ $value['id'] }}" class="accept-table-link" >
                                <i class="material-icons accept  small circle">grade</i>
                            </a>
                        </td>
                    @endif


                    @if (in_array('reject' , $actions))
                        <td>
                            <a href="#" data-model="{{ $util['model_name'] }}"  data-id="{{ $value['id'] }}" class="reject-table-link" >
                                <i class="material-icons accept  small circle">report_problem</i>
                            </a>
                        </td>
                    @endif

                </tr>
            @endforeach



        </tbody>


    </table>


</div>
