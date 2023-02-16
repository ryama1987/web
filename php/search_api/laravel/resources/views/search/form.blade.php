<x-app>
    <x-slot name="title">
        検索フォーム
    </x-slot>

    <form method="GET" action="/search/bool">
        <input id="input" type="search" name="kw" value="" class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md bg-white" autocomplete="off" spellcheck="false" role="combobox" placeholder="例：東京都　公認会計士" aria-live="polite">
    </form>

    @if(isset($results))
    <div class="my-5 p-5 border">
        <h3 class="mb-5">検索結果</h3>
       @forelse($results as $result)
       <p class="mt-10">{{ $result->Employment__c }}<br>{{ $result->Catch__c }} </p>
       <a href="https://career.jusnet.co.jp/search/detail.php?kno={{ $result->Name }}" class="block w-auto text-blue-600 hover:opacity-70">【{{ $result->Prefecture1__c }}：{{ $result->City1__c }}】{{ $result->Name }} の求人を見る</a>
       @empty
       @endforelse
    </div>
    @endif
</x-app>
