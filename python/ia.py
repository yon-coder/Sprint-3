import json

def gerar_dieta(peso, altura, objetivo):
    calorias = peso * 30 if objetivo == 'manter' else (peso * 35 if objetivo == 'ganhar' else peso * 25)
    return f"Dieta: {calorias} kcal por dia."

def gerar_treino(objetivo):
    if objetivo == 'ganhar':
        treino = "Treino de força 4x por semana"
    elif objetivo == 'perder':
        treino = "Cardio 5x por semana"
    else:
        treino = "Treino misto 3x por semana"
    return treino

# Exemplo de dados (poderia vir do banco de dados)
peso = 70
altura = 1.75
objetivo = 'ganhar'

dieta = gerar_dieta(peso, altura, objetivo)
treino = gerar_treino(objetivo)

print(json.dumps({"dieta": dieta, "treino": treino}))
