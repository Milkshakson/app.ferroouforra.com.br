
            <!-- Table with stripped rows -->
            <?php $meusBuyIns =session('meusBuyIns');?>
            <table class="table">
                <caption>Meus jogos</caption>
                <thead>
                    <tr>
                        <th scope="col">Jogo</th>
                        <th scope="col">Site</th>
                        <th scope="col">Buy in</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($meusBuyIns as $bi){?>
                    <tr>
                        <td><?=$bi['gameName']?></td>
                        <td><?=$bi['pokerSiteName']?></td>
                        <td><?=dolarFormat($bi['buyinValue'])?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- End Table with stripped rows -->