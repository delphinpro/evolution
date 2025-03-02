@extends('manager::template.page')
@section('content')
    @push('scripts.top')
        <script type="text/javascript">
            function deletegroup(groupid, type) {
                if(confirm("{{ ManagerTheme::getLexicon('confirm_delete_group') }}") === true) {
                    if(type === 'usergroup') {
                        document.location.href = "index.php?a=92&usergroup=" + groupid + "&operation=delete_user_group";
                    }
                    else if(type === 'documentgroup') {
                        document.location.href = "index.php?a=92&documentgroup=" + groupid + "&operation=delete_document_group";
                    }
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                var h1help = document.querySelector('h1 > .help');
                h1help.onclick = function() {
                    document.querySelector('.element-edit-message').classList.toggle('show')
                }
            });
        </script>
    @endpush
    <h1>
        <i class="{{ $_style['icon_web_user_access'] }}"></i>{{ ManagerTheme::getLexicon('web_access_permissions') }}<i class="{{ $_style['icon_question_circle'] }} help"></i>
    </h1>

    <div class="container element-edit-message">
        <div class="alert alert-info">{{ ManagerTheme::getLexicon('access_permissions_introtext') }}</div>
    </div>

    @if($modx->getConfig('use_udperms') !== true)
        <div class="container">
            <div class="alert alert-danger">{{ ManagerTheme::getLexicon('access_permissions_off') }}</div>
        </div>
    @endif

    <div class="tab-pane" id="wuapPane">
        <script type="text/javascript">
            tp1 = new WebFXTabPane(document.getElementById("wuapPane"), true);
        </script>

        <div class="tab-page" id="tabPage1">
            <h2 class="tab">{{ ManagerTheme::getLexicon('web_access_permissions_user_groups') }}</h2>
            <script type="text/javascript">tp1.addTabPage(document.getElementById("tabPage1"));</script>

            <div class="container container-body">
                <p class="element-edit-message-tab alert alert-warning">{{ ManagerTheme::getLexicon('access_permissions_users_tab') }}</p>
                <div class="form-group">
                    <b>{{ ManagerTheme::getLexicon('access_permissions_add_user_group') }}</b>
                    <form method="post" action="index.php" name="accesspermissions">
                        <input type="hidden" name="a" value="92" />
                        <input type="hidden" name="operation" value="add_user_group" />
                        <div class="input-group">
                            <input class="form-control" type="text" value="" name="newusergroup" />
                            <div class="input-group-btn">
                                <input class="btn btn-success" type="submit" value="{{ ManagerTheme::getLexicon('submit') }}" />
                            </div>
                        </div>
                    </form>
                </div>

                @if($userGroups->count() === 0)
                    <div class="text-danger">{{ ManagerTheme::getLexicon('no_groups_found') }}</div>
                @else
                    <?php /** @var EvolutionCMS\Models\MembergroupName $userGroup */?>
                    @foreach($userGroups as $userGroup)
                        <div class="form-group">
                            <form method="post" action="index.php" name="accesspermissions">
                                <input type="hidden" name="a" value="92" />
                                <input type="hidden" name="groupid" value="{{ $userGroup->getKey() }}" />
                                <input type="hidden" name="operation" value="rename_user_group" />
                                <div class="input-group">
                                    <input class="form-control" type="text" name="newgroupname" value="{{ $userGroup->name }}" />
                                    <div class="input-group-btn">
                                        <a target="main" class="btn btn-success" href="?a=91&list=users&id={{ $userGroup->getKey() }}&tab=0">{{ ManagerTheme::getLexicon('users_list') }}</a>
                                        <input class="btn btn-secondary" type="submit" value="{{ ManagerTheme::getLexicon('rename') }}" />
                                        <input class="btn btn-danger" type="button" value="{{ ManagerTheme::getLexicon('delete') }}" onclick="deletegroup({{ $userGroup->getKey() }}, 'usergroup');" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="tab-page" id="tabPage2">
            <h2 class="tab">{{ ManagerTheme::getLexicon('access_permissions_resource_groups') }}</h2>
            <script type="text/javascript">tp1.addTabPage(document.getElementById("tabPage2"));</script>

            <div class="container container-body">
                <p class="element-edit-message-tab alert alert-warning">{{ ManagerTheme::getLexicon('access_permissions_resources_tab') }}</p>
                <div class="form-group">
                    <b>{{ ManagerTheme::getLexicon('access_permissions_add_resource_group') }}</b>
                    <form method="post" action="index.php" name="accesspermissions">
                        <input type="hidden" name="a" value="92" />
                        <input type="hidden" name="operation" value="add_document_group" />
                        <div class="input-group">
                            <input class="form-control" type="text" value="" name="newdocgroup" />
                            <div class="input-group-btn">
                                <input class="btn btn-success" type="submit" value="{{ ManagerTheme::getLexicon('submit') }}" />
                            </div>
                        </div>
                    </form>
                </div>
                @if($documentGroups->count() === 0)
                    <div class="text-danger">{{ ManagerTheme::getLexicon('no_groups_found') }}</div>
                @else
                    <?php /** @var EvolutionCMS\Models\DocumentgroupName $documentGroup */?>
                    @foreach($documentGroups as $documentGroup)
                        <div class="form-group">
                            <form method="post" action="index.php" name="accesspermissions">
                                <input type="hidden" name="a" value="92" />
                                <input type="hidden" name="groupid" value="{{ $documentGroup->getKey() }}" />
                                <input type="hidden" name="operation" value="rename_document_group" />
                                <div class="input-group">
                                    <input class="form-control" type="text" name="newgroupname" value="{{ $documentGroup->name }}" />
                                    <div class="input-group-btn">
                                        <a target="main" class="btn btn-success" href="?a=91&list=documents&id={{ $documentGroup->getKey() }}&tab=1">{{ ManagerTheme::getLexicon('documents_list') }}</a>
                                        <input class="btn btn-secondary" type="submit" value="{{ ManagerTheme::getLexicon('rename') }}" />
                                        <input class="btn btn-danger" type="button" value="{{ ManagerTheme::getLexicon('delete') }}" onclick="deletegroup({{ $documentGroup->getKey() }},'documentgroup');" />
                                    </div>
                                </div>
                            </form>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        @if($documentGroups->count() > 0 && $userGroups->count() > 0)
            <div class="tab-page" id="tabPage3">
                <h2 class="tab">{{ ManagerTheme::getLexicon('access_permissions_links') }}</h2>
                <script type="text/javascript">tp1.addTabPage(document.getElementById("tabPage3"));</script>

                <div class="container container-body">
                    <p class="element-edit-message-tab alert alert-warning">{{ ManagerTheme::getLexicon('access_permissions_links_tab') }}</p>
                    <div class="form-group">
                        <b>{{ ManagerTheme::getLexicon('access_permissions_group_link') }}</b>
                        <form method="post" action="index.php" name="accesspermissions">
                            <input type="hidden" name="a" value="92" />
                            <input type="hidden" name="operation" value="add_document_group_to_user_group" />

                            {{ ManagerTheme::getLexicon('access_permissions_link_user_group') }}
                            <select name="usergroup">
                                <?php /** @var EvolutionCMS\Models\MembergroupName $userGroup */?>
                                @foreach($userGroups as $userGroup)
                                    <option value="{{ $userGroup->getKey() }}"> {{ $userGroup->name }}</option>
                                @endforeach
                            </select>

                            {{ ManagerTheme::getLexicon('access_permissions_link_to_group') }}
                            <select name="docgroup">
                                <?php /** @var EvolutionCMS\Models\DocumentgroupName $documentGroup */?>
                                @foreach($documentGroups as $documentGroup)
                                    <option value="{{ $documentGroup->getKey() }}"> {{ $documentGroup->name }}</option>
                                @endforeach
                            </select>
                            {{ ManagerTheme::getLexicon('access_permissions_context') }}

                            <label for="context_mgr"><input id="context_mgr" type="checkbox" value="0" name="context[]"/> mgr </label>
                            <label for="context_web"><input id="context_web" type="checkbox" value="1" name="context[]" /> web </label>

                            <br><input class="btn btn-success" type="submit" value="{{ ManagerTheme::getLexicon('submit') }}">
                        </form>
                    </div>
                    <hr>
                    <?php /** @var EvolutionCMS\Models\MembergroupName $userGroup */?>
                    @foreach($userGroups as $userGroup)
                        <ul>
                            <li>
                                <b>{{ $userGroup->name }}</b>
                                @if($userGroup->documentGroups->count() > 0)
                                    <ul>
                                        <?php /** @var EvolutionCMS\Models\DocumentgroupName $documentGroup */?>
                                        @foreach($userGroup->documentGroups as $documentGroup)
                                            <li>
                                                {{ $documentGroup->name }} ({{ $documentGroup->pivot->context ? 'web' : 'mgr' }})
                                                <small><i>(<a class="text-danger" href="index.php?a=92&coupling={{ $documentGroup->pivot->id }}&context={{ $documentGroup->pivot->context }}&operation=remove_document_group_from_user_group">{{ ManagerTheme::getLexicon('remove') }}</a>)</i></small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <i>{{ ManagerTheme::getLexicon('no_groups_found') }}</i>
                                @endif
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
