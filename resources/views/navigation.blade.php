@foreach($navigationSettings['buildings'] as $building)
    <h3 class="flex items-center text-1 p-2 text-xl bg-white-05 border-b border-white-20">
        <div class="mr-1 text-center w-8">
            <font-awesome-icon :icon="['far', 'building']"></font-awesome-icon>
        </div>
        <span>{{ $building }}</span>
    </h3>
    <ul class="list-none mb-8">
        @foreach($navigationSettings['blueprints'][$building] as $blueprint)
            <li class="bg-white-10 leading-tight border-b border-highlight hover:bg-white-20 transition-bg">
                <router-link :to="{
                        name: 'blueprintList',
                        params: {
                            blueprint: '{{ $blueprint['route'] }}'
                        }
                    }" class="no-underline text-justify text-7 text-xl flex items-center p-2"
                >
                    <div class="mr-1 text-center w-8">
                        <font-awesome-icon :icon="['fas', 'cogs']"></font-awesome-icon>
                    </div>

                    <div class="flex-1">{{ $blueprint['label'] }}</div>

                    @if($blueprint['count'] > 0)
                        <div class="bg-negative rounded-full p-2 text-sm text-white font-semibold">
                            {{ $blueprint['count'] }}
                        </div>
                    @endif
                </router-link>
            </li>
        @endforeach
    </ul>
@endforeach