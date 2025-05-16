import sys
import matplotlib.pyplot as plt

# Recebe os parâmetros de peso e altura
peso_atual = float(sys.argv[1])
altura_atual = float(sys.argv[2])

# Dados anteriores (simulados para exemplo, mas você pode alterar conforme necessário)
peso_anterior = 70.0  # Substituir pelo valor inicial
altura_anterior = 1.75  # Substituir pelo valor inicial

# Calcula o IMC
imc_anterior = peso_anterior / (altura_anterior ** 2)
imc_atual = peso_atual / (altura_atual ** 2)

# Evolução percentual
evolucao = ((imc_atual - imc_anterior) / imc_anterior) * 100

# Gerando o gráfico
plt.figure(figsize=(6, 4))
plt.plot(['Anterior', 'Atual'], [imc_anterior, imc_atual], marker='o', color='blue', linestyle='-', linewidth=2)
plt.title('Evolução do IMC')
plt.xlabel('Período')
plt.ylabel('IMC')
plt.grid(True)
plt.savefig('grafico.png')

print(f"Evolução do IMC: {evolucao:.2f}%")
