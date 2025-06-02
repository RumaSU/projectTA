<div class="cMainContentMailAppDashbaord">
    <ul class="listMailAppDashboard space-y-0.5">
        @for ($i = 0; $i < $randMail; $i++)
            @php
                $strRandomTest = Str::random();
            @endphp
            <li class="itmMailAppDashboard group">
                <div class="cItmMailAppDAshboard flex items-center bg-white rounded-md px-6 py-2.5 group-has-[:checked]:bg-blue-100">
                    <div class="checkItmMailApp">
                        <label for="itm-{{ $strRandomTest }}" class="size-6 rounded-lg bg-[#D9D9D9] group-has-[:checked]:bg-[#1565C0] block relative cursor-pointer">
                            <div class="checkIcon invisible opacity-0 text-white group-has-[:checked]:visible group-has-[:checked]:opacity-100 absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                                <div class="icn text-sm">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </label>
                        <input type="checkbox" id="itm-{{ $strRandomTest }}" class="sr-only hidden">
                    </div>
                    <div class="detailItmMailApp inline-flex items-center justify-between flex-grow w-full ml-4">
                        <div class="infoItmMailApp inline-flex items-center">
                            <div class="sent-byItmMailApp w-32">
                                <div class="txSentBy text-sm font-semibold">
                                    <p>${sent-by}</p>
                                </div>
                            </div>
                            <div class="messageItmMailApp inline-flex items-center gap-2">
                                <div class="subjectMessageItmMailApp">
                                    <div class="txSubjectMe text-sm font-semibold">
                                        <p>${subject-message}</p>
                                    </div>
                                </div>
                                -
                                <div class="descMessageItmMailApp">
                                    <div class="descMe text-sm">
                                        <p>${desc-message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dateItmMailApp">
                            <div class="txDate text-[0.65rem]">
                                <p>{{ Illuminate\Support\Carbon::now() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endfor
    </ul>
</div>
